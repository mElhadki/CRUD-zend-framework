<?php
namespace Article\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Article\Model\Article;
use Article\Model\Category;           
use Article\Form\ArticleForm;

class ArticleController extends AbstractActionController
 {
     protected $articleTable;
     protected $categoryTable;

     public function indexAction()
     {

      return new ViewModel(array(
         'articles' => $this->getArticleTable()->fetchAll(),
     ));


     }

     public function homeAction(){

        return new ViewModel(array(
            'articles' => $this->getArticleTable()->fetchAll(),
            'categories'=>$this->getCategoryTable()->fetchAll(),
        ));

     }

     public function addAction()
     {
      
        $categories = $this->getCategoryTable()->fetchAll();

        $category_options = array();

        foreach($categories as $category){

            $category_options[$category->id] = $category->name;
        }

        $form = new ArticleForm($category_options);

        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        
        if ($request->isPost()) {
          
            $article = new Article();
            $form->setInputFilter($article->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $article->exchangeArray($form->getData());
                $this->getArticleTable()->saveArticle($article);

                // Redirect to list of articles
                return $this->redirect()->toRoute('article');
            }
        }
        return array('form' => $form);
        
     }

     public function editAction()
     {

        //get id from the URL

        $id= (int) $this->params()->fromRoute('id',0);

        if(!$id){
            return $this->redirect()->toRoute('article',array(

                    'action' => 'add'
            ));
        }

        try{

                $article = $this->getArticleTable()->getArticle($id);

        } catch (\Exception $ex) {

            return $this->redirect()->toRoute('article',array(

                'action' => 'index'
            ));
        }

        $categories = $this->getCategoryTable()->fetchAll();

        $category_options = array();

        foreach($categories as $category){

            $category_options[$category->id] = $category->name;
        }

        $form = new ArticleForm($category_options);
        $form->bind($article);

        $form->get('submit')->setAttribute('value','edit');

        $request = $this->getRequest();
        
        if ($request->isPost()) {
          
            
            $form->setInputFilter($article->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                
                $this->getArticleTable()->saveArticle($article);

                // Redirect to list of articles
                return $this->redirect()->toRoute('article');
            }
        }
        return array('form' => $form,
                     'id' => $id
    );
    
     }

     public function deleteAction()
     {
         //get id from the URL

         $id= (int) $this->params()->fromRoute('id',0);

         if(!$id){
             return $this->redirect()->toRoute('article',array(
 
                     'action' => 'add'
             ));
         }

         $request = $this->getRequest();
         if($request->isPost()){

            $del = $request->getPost('del','No');

            if($del == 'Yes'){
                $id = (int) $request->getPost('id');
                $this->getArticleTable()->deleteArticle($id);
            }

            return $this->redirect()->toRoute('article');

         }

         return array(
             'id' => $id,
             'article' => $this->getArticleTable()->getArticle($id)
         );
         
     }

     public function viewAction(){

         //get id from the URL

         $id= (int) $this->params()->fromRoute('id',0);

         if(!$id){
 
             return $this->redirect()->toRoute('article');
         }
         try{
 
             $article = $this->getArticleTable()->getArticle($id);
 
         } catch (\Exception $ex){
 
             return $this->redirect()->toRoute('article');
         }
 
       
         return array(
             'id' => $id,
             'article' => $article
         );

     }


     public function getArticleTable()
     {
         if (!$this->articleTable) {
             $sm = $this->getServiceLocator();
             $this->articleTable = $sm->get('Article\Model\ArticleTable');
         }
         return $this->articleTable;
     }
     public function getCategoryTable()
     {
         if (!$this->categoryTable) {
             $sm = $this->getServiceLocator();
             $this->categoryTable = $sm->get('Article\Model\CategoryTable');
         }
         return $this->categoryTable;
     }
 }