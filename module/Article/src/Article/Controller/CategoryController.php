<?php
namespace Article\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Article\Model\Category;           
use Article\Form\CategoryForm;
use Article\Model\Article;   

class CategoryController extends AbstractActionController
 {
   protected $articleTable;
   protected $categoryTable;

   public function indexAction()
   {
    return new ViewModel(array(
       'categories' => $this->getCategoryTable()->fetchAll(),
   ));
   }

     public function addAction()
     {
       

        $form = new CategoryForm();

        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $category = new Category();
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $category->exchangeArray($form->getData());
                $this->getCategoryTable()->saveCategory($category);

                // Redirect to list of articles
                return $this->redirect()->toRoute('category');
            }
        }
        return array('form' => $form);
        
     }

     public function editAction()
     {
          //get id from the URL

          $id= (int) $this->params()->fromRoute('id',0);

          if(!$id){
              return $this->redirect()->toRoute('category',array(
  
                      'action' => 'add'
              ));
          }
  
          try{
  
                  $category = $this->getCategoryTable()->getCategory($id);
  
          } catch (\Exception $ex) {
  
              return $this->redirect()->toRoute('category',array(
  
                  'action' => 'index'
              ));
          }
  
         
  
          $form = new CategoryForm();

          $form->bind($category);
  
          $form->get('submit')->setAttribute('value','edit');
  
          $request = $this->getRequest();
          
          if ($request->isPost()) {
            
              
              $form->setInputFilter($category->getInputFilter());
              $form->setData($request->getPost());
  
              if ($form->isValid()) {
                  
                  $this->getCategoryTable()->saveCategory($category);
  
                  // Redirect to list of articles
                  return $this->redirect()->toRoute('category');
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
             return $this->redirect()->toRoute('category',array(
 
                     'action' => 'add'
             ));
         }

         $request = $this->getRequest();
         if($request->isPost()){

            $del = $request->getPost('del','No');

            if($del == 'Yes'){
                $id = (int) $request->getPost('id');
                $this->getCategoryTable()->deleteCategory($id);
            }

            return $this->redirect()->toRoute('category');

         }

         return array(
             'id' => $id,
             'category' => $this->getCategoryTable()->getCategory($id)
         );
     }
     
     public function viewAction(){

        //get id from the URL

        $id= (int) $this->params()->fromRoute('id',0);

        if(!$id){

            return $this->redirect()->toRoute('category');
        }
        try{

            $category = $this->getCategoryTable()->getCategory($id);

        } catch (\Exception $ex){

            return $this->redirect()->toRoute('category');
        }

        $artciles = $this->getArticleTable()->fetchCategoryArticles($id);
        return array(
            'id' => $id,
            'category' => $category,
            'articles' => $artciles
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