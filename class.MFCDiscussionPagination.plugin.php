<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['MFCDiscussionPagination'] = array(
   'Name' => 'Front Page Discussions Pagination',
   'Description' => 'Creates pagelist navigation on items in Discussions view.',
   'Version' => '1.03',
   'Author' => "stwc",
   'AuthorEmail' => 'wonderchickenindustries@gmail.com',
   'AuthorUrl' => 'http://wonderchicken.com',
   'RequiredApplications' => array('Vanilla' => '2.0.17'),
   'MobileFriendly' => TRUE
);

class MFCDiscussionPagination implements Gdn_IPlugin {

   public function Base_Render_Before($Sender) {
      $Sender->AddCssFile('plugins/MFCDiscussionPagination/design/mfcdiscussionpagination.css');
   }
   
   public function DiscussionsController_AfterDiscussionTitle_Handler(&$Sender) {
		$Discussion = $Sender->EventArguments['Discussion'];
		//$CommentsPerPage = C('Vanilla.Comments.PerPage');
		// Actual number of comments, excluding the discussion itself
		$CommentCount = $Discussion->CountComments - 1;
		$DiscussionID = $Discussion->DiscussionID;
		$DiscussionName = $Discussion->Name;
		$DiscussionID = (is_numeric($DiscussionID) && $DiscussionID > 0) ? $DiscussionID : 0;
		
		if (!is_numeric($Limit) || $Limit < 0)
         $Limit = C('Vanilla.Comments.PerPage', 100);
		
		// Build a pager
      $PagerFactory = new Gdn_PagerFactory();
		$Sender->EventArguments['PagerType'] = 'Pager';
		$Sender->FireEvent('BeforeBuildPager');
      $Sender->Pager = $PagerFactory->GetPager($Sender->EventArguments['PagerType'], $Sender);
      $Sender->Pager->ClientID = 'Pager';
      $Sender->Pager->Wrapper = '<span %1$s>%2$s</span>';   
      $Sender->Pager->Configure(
         $Sender->Offset,
         $Limit,
         $CommentCount,
         'discussion/'.$DiscussionID.'/'.Gdn_Format::Url($DiscussionName).'/%1$s'
      );
      $Sender->FireEvent('AfterBuildPager');
	 echo $Sender->Pager->ToString('more');
	  
		
		//testing stuff
		// echo Wrap($Limit . ' per page with' . $CommentCount . ' for discussion #' . $DiscussionID, 'div', array(
            // 'class'  => "Paginato"
         // ));
	    

   }
   
   public function Setup() {
      //no setup needed
   }
} 

?>