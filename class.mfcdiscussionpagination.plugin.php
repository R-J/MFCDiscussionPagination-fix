<?php

// Define the plugin:
$PluginInfo['MFCDiscussionPagination'] = array(
   'Name' => 'Front Page Discussions Pagination',
   'Description' => 'Creates pagelist navigation on items in Discussions view.',
   'Version' => '1.04',
   'Author' => "stwc",
   'AuthorEmail' => 'wonderchickenindustries@gmail.com',
   'AuthorUrl' => 'http://wonderchicken.com',
   'RequiredApplications' => array('Vanilla' => '2.5'),
   'MobileFriendly' => TRUE
);

class MFCDiscussionPaginationPlugin extends Gdn_Plugin {

   public function Base_Render_Before($Sender) {
      $Sender->AddCssFile('plugins/MFCDiscussionPagination/design/mfcdiscussionpagination.css');
   }

   public function DiscussionsController_AfterDiscussionTitle_Handler($Sender) {
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
      $Sender->Pager->MoreCode = '';
      $Sender->Pager->LessCode = '';
      $Sender->Pager->CssClass = 'MiniPager';
      $Sender->Pager->ClientID = 'Pager'.$Discussion->DiscussionID;
      $Sender->Pager->Wrapper = '<span %1$s>%2$s</span>';
      $Sender->Pager->Configure(
         $Sender->Offset,
         $Limit,
         $CommentCount,
         'discussion/'.$DiscussionID.'/'.Gdn_Format::Url($DiscussionName).'/%1$s'
      );
      $Sender->FireEvent('AfterBuildPager');
	 echo $Sender->Pager->ToString('more');
   }
}
