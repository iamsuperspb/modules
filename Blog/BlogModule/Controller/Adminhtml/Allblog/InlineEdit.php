<?php
namespace Blog\BlogModule\Controller\Adminhtml\Allblog;

use Magento\Backend\App\Action\Context;
use Blog\BlogModule\Api\AllblogRepositoryInterface as AllblogRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Blog\BlogModule\Api\Data\AllblogInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $allblogRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    public function __construct(
        Context $context,
        AllblogRepository $allblogRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->allblogRepository = $allblogRepository;
        $this->jsonFactory = $jsonFactory;
    }
	
	/**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Blog_BlogModule::save');
	}

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $blogId) {
            $blog = $this->allblogRepository->getById($blogsId);
            try {
                $blogData = $postItems[$blogId];
                $extendedBlogData = $blog->getData();
                $this->setBlogData($blog, $extendedBlogData, $blogData);
                $this->allblogRepository->save($blog);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithBlogId($blog, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithBlogId($blog, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithBlogId(
                    $blog,
                    __('Something went wrong while saving the blog.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function getErrorWithBlogId(AllblogInterface $blog, $errorText)
    {
        return '[Blog ID: ' . $blog->getId() . '] ' . $errorText;
    }

    public function setBlogData(\Blog\BlogModule\Model\Allblog $blog, array $extendedblogData, array $blogData)
    {
        $blog->setData(array_merge($blog->getData(), $extendedBlogData, $blogData));
        return $this;
    }
}
