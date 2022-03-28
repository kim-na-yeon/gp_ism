<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/channel/ChannelMgr.php";

$imst_idx = RequestUtil::getParam("imst_idx", "");

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
$wq->addAndString("imst_idx","=",$imst_idx);
$wq->addOrderBy("imst_idx","asc");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = ChannelMgr::getInstance()->getList($wq);

echo "<option value=''>거래처(채널) 선택</option>";

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_channel = $rs->fetch_assoc();
?>        
        <option value="<?=$row_channel['imc_idx']?>"><?="[".$row_channel['sales_type_title']."] ".$row_channel['name']?></option>
<?php        
    }
}

@$rs->free();
exit;
?>