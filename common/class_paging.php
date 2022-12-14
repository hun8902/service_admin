<?
class PAGING
{
  var $curPage;       //현재페이지
  var $first;         //게시물 첫페이지
  var $last;          //게시물 끝페이지
  var $firstPage;     //루프 시작페이지
  var $lastPage;      //루프 끝페이지
  var $curBlock;      //현재 블럭
  var $pageSize;      //한페이지에 출력한 게시물수
  var $blockSize;     //한블럭에 출력할 페이지 수
  var $totalPage;     //전체 페이지 수
  var $totalBlock;    //전체 블럭 수
  var $totalCount;    //전체 게시물 수
  var $mypage;
  var $directPage;
  var $getParams;     //파라메타
  var $baseUrl;
  var $iconDir;       //페이지 아이콘

  function PAGING($totRec, $page, $pgSize, $blcSize, $skin)
  {
    //(!$page) ? $this->curPage = 1 : $this->curPage = $page;
    //if(!$page) $this->curPage = 1; else $this->curPage = $page;
    $this->curPage = $page;
    $this->pageSize = $pgSize;
    $this->blockSize = $blcSize;
    $this->totalCount = $totRec;
    $this->curBlock = ceil($this->curPage/$this->blockSize);
    $this->totalPage = ceil($this->totalCount/$pgSize);
    $this->totalBlock = ceil($this->totalPage/$this->blockSize);
    $this->firstPage = ($this->curBlock-1)*$this->blockSize;
    if($this->totalBlock <= $this->curBlock) $this->lastPage = $this->totalPage;
    else                                     $this->lastPage = $this->curBlock*$this->blockSize;
    $this->baseUrl = $_SERVER['PHP_SELF'];
    if($this->totalCount == 0) { $this->first = 1; $this->last = 0; }
    else                       { $this->first = $this->pageSize*($this->curPage-1); $this->last = $this->pageSize*$this->curPage; }
    $this->iconDir = "/bbs/skin/bbs/".$skin."/images";
  }

  function addQueryString($params)
  {
    $this->getParams = $params;
  }

  function showPage()
  {
    // 이전 10개
    /*
     if($this->curBlock > 1)
    {
      $this->myPage = $this->firstPage;
      echo '<li class="paginate_button page-item page_arr page_first"><a class="page-link" href="'.$this->baseUrl.'?page='.$this->myPage.''.$this->getParams.'" class="icon-chevrons-left"></a></li>';
    }
    else {
      echo '<li class="paginate_button page-item page_arr page_first"><a href="javascript:;" class="page-link icon-chevrons-left"></a></li>';
    }
    */

    // 이전 1개
    if($this->curPage!=1)
    {
      $this->myPage = $this->curPage-1;
      echo '<li class="paginate_button page-item page_arr page_prev"><a class="page-link" href="'.$this->baseUrl.'?page='.$this->myPage.''.$this->getParams.'" >이전</a></li>';
    }
    else {
      echo '<li class="paginate_button page-item page_arr page_prev disabled"><a href="javascript:;" class="page-link">이전</a></li>';
    }


    // 각페이지
    for($this->directPage = $this->firstPage+1; $this->directPage <= $this->lastPage; $this->directPage++)
    {
      if($this->curPage == $this->directPage)
      {
        echo '<li class="active paginate_button page-item"><a controls="class_table" class="page-link" href="javascript:;">'.$this->directPage.'</a>';

      }
      else
      {
        echo '<li class="paginate_button page-item "><a aria-controls="class_table" class="page-link" href="'.$this->baseUrl.'?page='.$this->directPage.''.$this->getParams.'">'.$this->directPage.'</a>';
      }
    }

    // 다음 1개
    if($this->curPage < $this->totalPage)
    {
      $this->myPage = $this->curPage+1;
      echo '<li class="paginate_button page-item page_arr page_next"><a class="page-link" href="'.$this->baseUrl.'?page='.$this->myPage.''.$this->getParams.'" >다음</a></li>';
    }
    else
    {
      echo '<li class="paginate_button page-item page_arr page_next disabled"><a href="javascript:;" class="page-link">다음</a></li>';
    }

    // 다음 10개
      /*
    if($this->curBlock < $this->totalBlock)
    {
      $this->myPage = $this->lastPage+1;
      echo '<li class="paginate_button page-item page_arr page_last"><a class="page-link" href="'.$this->baseUrl.'?page='.$this->myPage.''.$this->getParams.'" class="icon-chevrons-right"></a></li>';
    }
    else
    {
      echo '<li class="paginate_button page-item page_arr page_last"><a href="javascript:;" class="page-link icon-chevrons-right"></a></li>';
    }
      */

  }
}
?>