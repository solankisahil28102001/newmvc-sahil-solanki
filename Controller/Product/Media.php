<?php 

class Controller_Product_Media extends Controller_Core_Action{

	public function gridAction()
	{
		try {
			$layout = $this->getLayout();
			$grid = $layout->createBlock('Product_Media_Grid');

			if (!$productId = $this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			$layout->getChild('content')->addChild('grid', $grid);
			$layout->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('grid', 'product', null, true);
		}
	}


	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$add = $layout->createBlock('Product_Media_Add');

			if (!$productId = $this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			$layout->getChild('content')->addChild('add', $add);
			$layout->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
			$this->redirect('grid');
		}
	}
	
	public function insertAction()
	{
		try {
			if (!$this->getRequest()->isPost()) {
				throw new Exception("Invalid request.", 1);
			}

			$name = $this->getRequest()->getPost('name');
			$status = $this->getRequest()->getPost('status');
			$filename = $_FILES['image']['name'];
			
			if (!$filename) {
				throw new Exception("Image not posted.", 1);
			}

			if (!$productId = $this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			$media = ['product_id'=>$productId,'name'=>$name,'image'=>$filename,'status'=>$status,'created_at'=>date("Y/m/d H:i:s")];
			
			if (!$insert_id = Ccc::getModel('Product_Media')->setData($media)->save()) {
				throw new Exception("Unable to insert media.", 1);
			}

			$array = explode('.', $filename);
			$extension = $array[1];
			$filename = $insert_id.'.'.$extension;
			$destination = 'Resources/images/'.$filename;
			$result = move_uploaded_file($_FILES['image']['tmp_name'],$destination);

			if(!$result){
				throw new Exception("Unable to save image.", 1);
			}

			;
			if (!$result = Ccc::getModel('Product_Media')->setData(['image'=>$filename, 'media_id'=>$insert_id])->save()) {
				throw new Exception("Unable to update filename.", 1);
			}
			$this->getMessage()->addMessage("Media added successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);	
		}
		$this->redirect('grid');
	}

	public function mediaAction()
	{
		try {
			if (!$this->getRequest()->isPost()) {
				throw new Exception("Invalid request.", 1);
			}

			$operation = $this->getRequest()->getPost('operation');
			if (!$product_media = $this->getRequest()->getPost('product_media')){
				throw new Exception("No data posted.", 1);
			}

			if (!$productId = $this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			$gallery = (array_key_exists('gallery', $product_media)) ? $product_media['gallery'] : [];
			$strGallery = implode("," ,$gallery);
			$delete = (array_key_exists('delete', $product_media)) ? $product_media['delete'] : [];
			$small = (array_key_exists('small', $product_media)) ? (int)$product_media['small'] : null;
			$thumb = (array_key_exists('thumb', $product_media)) ? (int)$product_media['thumb'] : null;
			$base = (array_key_exists('base', $product_media)) ? (int)$product_media['base'] : null;

			// $radioData = [$small,$thumb,$base];
			// foreach ($radioData as $key => $value) {
			// 	if ($radioData[$key] == null) {
			// 		unset($radioData[$key]);
			// 	}
			// }
			// $radioData = implode(",", $radioData);
			// if (!$productId = $this->getRequest()->getParam('id')) {
			// 	throw new Exception("Invalid request.", 1);
			// }
				
			if ($operation == 'Update') {
				$media = Ccc::getModel('Product_Media');
				$media->getResource()->setPrimaryKey('product_id');
				if (!$media->setData(['gallery'=>0, 'product_id'=>$productId])->save()) {
					throw new Exception("Unable to update Media.", 1);
				}

				if (!$result = Ccc::getModel('Product')->setData(['small_id'=>$small, 'thumb_id'=>$thumb, 'base_id'=>$base, 'product_id'=>$productId])->save()){
					throw new Exception("Unable to update product data.", 1);
				}

				if (!$gallery) {
					$this->redirect('grid');
				}

				if (!$result = Ccc::getModel('Product_Media')->setData(['gallery'=>1, 'media_id'=>[$strGallery]])->save()){
					throw new Exception("Unable to update gallery.", 1);
				}

				$this->getMessage()->addMessage("Media updated successfully.");
			}

			else if ($operation == 'Delete selected') {
				if (!$delete) {
					$this->redirect('grid');
				}

				if (!$result = Ccc::getModel('Product_Media')->setData(['media_id'=>$delete])->delete()) {	
					throw new Exception("Unable to delete media.", 1);
				}

				$this->getMessage()->addMessage("Media deleted successfully.");
			}
			else{
				throw new Exception("Invalid operation.", 1);
			}
		}
		catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);	
		}
		$this->redirect('grid');
	}
}

