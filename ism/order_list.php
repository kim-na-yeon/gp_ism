<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsItemMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/brand/BrandMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/channel/ChannelMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/category/CategoryMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/sales_type/SalesTypeMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/status/StatusMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/order/OrderMgr.php";

$menuCate = 1;
$menuNo = 1;

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");

$_order_date_from = RequestUtil::getParam("_order_date_from", date("Y-m-01"));
$_order_date_to = RequestUtil::getParam("_order_date_to", date("Y-m-d"));
$_imc_idx = RequestUtil::getParam("_imc_idx", "");
$_imb_idx = RequestUtil::getParam("_imb_idx", "");
$_cate1_idx = RequestUtil::getParam("_cate1_idx", "");
$_cate2_idx = RequestUtil::getParam("_cate2_idx", "");
$_cate3_idx = RequestUtil::getParam("_cate3_idx", "");
$_cate4_idx = RequestUtil::getParam("_cate4_idx", "");
$_tax_type = RequestUtil::getParam("_tax_type", "");
$_order_type = RequestUtil::getParam("_order_type", "");
$_goods_mst_code = RequestUtil::getParam("_goods_mst_code", "");
$_goods_name = RequestUtil::getParam("_goods_name", "");
$_item_code = RequestUtil::getParam("_item_code", "");
$_item_name = RequestUtil::getParam("_item_name", "");
$_except_cancel = RequestUtil::getParam("_except_cancel", "");
$_status = RequestUtil::getParam("_status", "");
$_order_no = RequestUtil::getParam("_order_no", "");

$_imc_idx_2 = RequestUtil::getParam("_imc_idx_2", "");
$_imb_idx_2 = RequestUtil::getParam("_imb_idx_2", "");
$_cate1_idx_2 = RequestUtil::getParam("_cate1_idx_2", "");
$_cate2_idx_2 = RequestUtil::getParam("_cate2_idx_2", "");
$_cate3_idx_2 = RequestUtil::getParam("_cate3_idx_2", "");
$_cate4_idx_2 = RequestUtil::getParam("_cate4_idx_2", "");
$_tax_type_2 = RequestUtil::getParam("_tax_type_2", "");
$_order_type_2 = RequestUtil::getParam("_order_type_2", "");
$_goods_mst_code_2 = RequestUtil::getParam("_goods_mst_code_2", "");
$_goods_name_2 = RequestUtil::getParam("_goods_name_2", "");
$_item_code_2 = RequestUtil::getParam("_item_code_2", "");
$_item_name_2 = RequestUtil::getParam("_item_name_2", "");
$_status_2 = RequestUtil::getParam("_status_2", "");

$_order_by = RequestUtil::getParam("_order_by", "order_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

if (empty($_imc_idx) && $_imc_idx_2) {
    $_imc_idx = $_imc_idx_2;
}
    
if (empty($_imb_idx) && $_imb_idx_2) {
    $_imb_idx = $_imb_idx_2;
}

if (empty($_cate1_idx) && $_cate1_idx_2) {
    $_cate1_idx = $_cate1_idx_2;
}

if (empty($_cate2_idx) && $_cate2_idx_2) {
    $_cate2_idx = $_cate2_idx_2;
}

if (empty($_cate3_idx) && $_cate3_idx_2) {
    $_cate3_idx = $_cate3_idx_2;
}

if (empty($_tax_type) && $_tax_type_2) {
    $_tax_type = $_tax_type_2;
}

if (empty($_order_type) && $_order_type_2) {
    $_order_type = $_order_type_2;
}

if (empty($_goods_mst_code) && $_goods_mst_code_2) {
    $_goods_mst_code = $_goods_mst_code_2;
}

if (empty($_goods_name) && $_goods_name_2) {
    $_goods_name = $_goods_name_2;
}

if (empty($_item_code) && $_item_code_2) {
    $_item_code = $_item_code_2;
}

if (empty($_item_name) && $_item_name_2) {
    $_item_name = $_item_name_2;
}

if (empty($_status) && $_status_2) {
    $_status = $_status_2;
}

$pg = new Page($currentPage, $pageSize);

$arrDayOfWeek = array("일","월","화","수","목","금","토");

$arrChannel = $arrBrand = $arrCategory1 = $arrCategory2 = $arrCategory3 = $arrCategory4 = $arrSalesType = $arrStatus = array();

$wq = new WhereQuery(true, true);
$wq->addOrderBy("sort","asc");
$rs = StatusMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        array_push($arrStatus, $row);
    }
}

$wq = new WhereQuery(true, true);
$rs = SalesTypeMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        $arrSalesType[$row["imst_idx"]] = $row["title"];
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");

$wq->addOrderBy("imst_idx","");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = ChannelMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_channel = $rs->fetch_assoc();
        
        array_push($arrChannel, $row_channel);
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imb_fg_del","=","0");

$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = BrandMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_brand = $rs->fetch_assoc();
        
        array_push($arrBrand, $row_brand);
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imct_fg_del","=","0");
$wq->addAndString("depth","=","1");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("title","asc");

$rs = CategoryMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_category = $rs->fetch_assoc();
        
        array_push($arrCategory1, $row_category);
    }
}


if($_cate1_idx && $_cate1_idx > 0) {
    $wq = new WhereQuery(true, true);
    $wq->addAndString2("imct_fg_del","=","0");
    $wq->addAndString("depth","=","2");
    $wq->addAndString("upper_imct_idx","=",$_cate1_idx);
    $wq->addOrderBy("sort","desc");
    $wq->addOrderBy("title","asc");
    
    $rs = CategoryMgr::getInstance()->getList($wq);
    
    if($rs->num_rows > 0) {
        for($i=0;$i<$rs->num_rows;$i++) {
            $row_category = $rs->fetch_assoc();
            
            array_push($arrCategory2, $row_category);
        }
    }
}

if($_cate2_idx && $_cate2_idx > 0) {
    $wq = new WhereQuery(true, true);
    $wq->addAndString2("imct_fg_del","=","0");
    $wq->addAndString("depth","=","3");
    $wq->addAndString("upper_imct_idx","=",$_cate2_idx);
    $wq->addOrderBy("sort","desc");
    $wq->addOrderBy("title","asc");
    
    $rs = CategoryMgr::getInstance()->getList($wq);
    
    if($rs->num_rows > 0) {
        for($i=0;$i<$rs->num_rows;$i++) {
            $row_category = $rs->fetch_assoc();
            
            array_push($arrCategory3, $row_category);
        }
    }
}

if($_cate3_idx && $_cate3_idx > 0) {
    $wq = new WhereQuery(true, true);
    $wq->addAndString2("imct_fg_del","=","0");
    $wq->addAndString("depth","=","4");
    $wq->addAndString("upper_imct_idx","=",$_cate3_idx);
    $wq->addOrderBy("sort","desc");
    $wq->addOrderBy("title","asc");
    
    $rs = CategoryMgr::getInstance()->getList($wq);
    
    if($rs->num_rows > 0) {
        for($i=0;$i<$rs->num_rows;$i++) {
            $row_category = $rs->fetch_assoc();
            
            array_push($arrCategory4, $row_category);
        }
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString("order_date", ">=", $_order_date_from);
$wq->addAndStringBind("order_date", "<", $_order_date_to, "date_add('?', interval 1 day)");
$wq->addAndString("imc_idx", "=", $_imc_idx);
$wq->addAndString("imb_idx", "=", $_imb_idx);
$wq->addAndString("cate1_idx", "=", $_cate1_idx);
$wq->addAndString("cate2_idx", "=", $_cate2_idx);
$wq->addAndString("cate3_idx", "=", $_cate3_idx);
$wq->addAndString("cate4_idx", "=", $_cate4_idx);
$wq->addAndString("tax_type", "=", $_tax_type);
$wq->addAndString("order_type", "=", $_order_type);
$wq->addAndString("goods_mst_code", "=", $_goods_mst_code);
$wq->addAndString("a.item_code", "=", $_item_code);
$wq->addAndString("status", "=", $_status);
$wq->addAndString("order_no", "=", $_order_no);

$wq->addAndLike("name",$_goods_name);
$wq->addAndLike("item_name",$_item_name);

if($_except_cancel) {
    $wq->addAndNotIn("status", array("취소접수","취소완료","삭제"));
}

$wq->addOrderBy($_order_by, $_order_by_asc);

if ($_order_by=="cate1_name") {
    $wq->addOrderBy("cate2_name", "asc");
    $wq->addOrderBy("cate3_name", "asc");
    $wq->addOrderBy("cate4_name", "asc");
}

$wq->addOrderBy("order_date", "desc");

$rs = OrderMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">

    <input type="hidden" name="_order_date_from" value="<?=$_order_date_from?>">
    <input type="hidden" name="_order_date_to" value="<?=$_order_date_to?>">
    <input type="hidden" name="_imc_idx" value="<?=$_imc_idx?>">
    <input type="hidden" name="_imb_idx" value="<?=$_imb_idx?>">
    <input type="hidden" name="_cate1_idx" value="<?=$_cate1_idx?>">
    <input type="hidden" name="_cate2_idx" value="<?=$_cate2_idx?>">
    <input type="hidden" name="_cate3_idx" value="<?=$_cate3_idx?>">
    <input type="hidden" name="_cate4_idx" value="<?=$_cate4_idx?>">
    <input type="hidden" name="_tax_type" value="<?=$_tax_type?>">
    <input type="hidden" name="_order_type" value="<?=$_order_type?>">
    <input type="hidden" name="_goods_mst_code" value="<?=$_goods_mst_code?>">
    <input type="hidden" name="_goods_name" value="<?=$_goods_name?>">
	<input type="hidden" name="_item_code" value="<?=$_item_code?>">
	<input type="hidden" name="_item_name" value="<?=$_item_name?>">
	<input type="hidden" name="_except_cancel" value="<?=$_except_cancel?>">
	<input type="hidden" name="_status" value="<?=$_status?>">
	<input type="hidden" name="_order_no" value="<?=$_order_no?>">
    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
</form>

            <!-- 상품검색(s) -->
            <div>
                <div style="padding-left:20px;">
                    <h3 class="icon-search">판매 내역 검색</h3>
                    <ul class="icon_Btn">
                        <li><a href="#" name="btnExcelDownload">엑셀</a></li>
                    </ul>
                </div>
				<form name="searchForm" method="get" action="order_list.php">
				
                    <table class="adm-table">
                        <caption>상품 검색</caption>
                        <colgroup>
                            <col style="width:8%;">
                            <col style="width:25%;">
                            <col style="width:9%;">
                            <col style="width:25%;">
                            <col style="width:8%;">
                            <col style="width:25%;">
                        </colgroup>
                        <tbody>
							<tr>
                                <th>판매일자</th>
                                <td><input type="date" id="_order_date_from" name="_order_date_from" class="date_in" value="<?=$_order_date_from?>" style="padding:0 16px;">~<input type="date" id="_order_date_to" name="_order_date_to" value="<?=$_order_date_to?>" class="date_in" style="padding:0 16px;"></td>
                            	<th>판매유형/거래처(채널)</th>
                            	<td colspan="3">
									<select name="_order_type" class="sel_order_type">
                                    	<option value="">판매 유형</option>
<?php                                     	
foreach($arrSalesType as $key => $value) {
?>
                                    	<option value="<?=$key?>" <?=$_order_type==$key?"selected":""?>><?=$value?></option>
<?php
}
?>
                                    </select>
                                    <select name="_imc_idx" class="sel_channel">
                						<option value="">거래처(채널) 선택</option>
                						<?php
                						foreach($arrChannel as $lt){
                							?>
                							<option value="<?=$lt['imc_idx']?>" <?=$_imc_idx==$lt['imc_idx']?"selected":""?>><?="[".$lt['sales_type_title']."] ".$lt['name']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>                           
							</tr>
							<tr>
                                <th>카테고리</th>
                                <td colspan="3">
									<select name="_cate1_idx" class="sel_category" depth="1">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory1 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate1_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate2_idx" class="sel_category" depth="2" style="<?=(count($arrCategory2)>0)?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory2 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate2_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate3_idx" class="sel_category" depth="3" style="<?=(count($arrCategory3)>0)?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory3 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate3_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate4_idx" class="sel_category" depth="4" style="<?=(count($arrCategory4)>0)?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory4 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate4_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>
                                <th>브랜드</th>
                                <td>
                                    <select name="_imb_idx" class="select_brand">
                						<option value="">브랜드 선택</option>
                						<?php
                						foreach($arrBrand as $lt){
                							?>
                							<option value="<?=$lt['imb_idx']?>" <?=$_imb_idx==$lt['imb_idx']?"selected":""?>><?=$lt['name']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>
                            </tr>
                            <tr>
                            	<th>상품코드</th>
                            	<td><input type="text" placeholder="상품코드로 검색" name="_goods_mst_code" style="width: 100%;" value=<?=$_goods_mst_code?>></td>
                            	<th>상품명</th>
                            	<td><input type="text" placeholder="상품명으로 검색" name="_goods_name" style="width: 100%;" value=<?=$_goods_name?>></td>
                                <th>과세구분</th>
                                <td>
                                	<select name="_tax_type">
                                    	<option value="">과세 구분</option>
                                    	<option value="과세" <?=$_tax_type=="과세"?"selected":""?>>과세</option>
                                    	<option value="면세" <?=$_tax_type=="면세"?"selected":""?>>면세</option>
                                    </select>
								</td>
                            </tr>
                            <tr>
                            	<th>품목(옵션)코드</th>
                            	<td><input type="text" placeholder="품목(옵션)명으로 검색" name="_item_code" style="width: 100%;" value=<?=$_item_code?>></td>
                            	<th>품목(옵션)명</th>
                            	<td><input type="text" placeholder="품목(옵션)명으로 검색" name="_item_name" style="width: 100%;" value=<?=$_item_name?>></td>
                            	<th>상태</th>
                            	<td>
<select name="_status" class="sel_status">
                                    	<option value="">상태</option>
<?php          
foreach($arrStatus as $lt){
?>
                							<option value="<?=$lt['title_status']?>" <?=$_status==$lt['title_status']?"selected":""?>><?=$lt['title_status']?></option>
                							<?php
}
?>
                                    </select>
                            	<input type="checkbox" value="1" name="_except_cancel" id="_except_cancel" <?=$_except_cancel?"checked='checked'":""?>><label for="_except_cancel">취소/삭제 제외</label></td>
                            </tr>
                        </tbody>
                    </table>
    				<!-- 검색버튼 START -->
    				<div class="wms_searchBtn">
    					<a href="#" class="ism_btnSearch" name="btnSearch">검색</a>
    				</div>
    				<!-- 검색버튼 END -->
				</form>
			</div>
			<!-- 상품검색(e) -->
                
			<div class="float-wrap">
				<h3 class="float-l">총 판매 <strong><?=number_format($pg->getTotalCount())?>건</strong></h3>
				<p class="list-adding float-r">
					<a href="#none" name="_btn_sort" order_by="order_date" order_by_asc="desc" class="<?=$_order_by=="order_date" && $_order_by_asc=="desc"?"on":""?>" >판매일순<em>▼</em></a>
					<a href="#none" name="_btn_sort" order_by="name" order_by_asc="asc" class="<?=$_order_by=="name" && $_order_by_asc=="asc"?"on":""?>">상품명<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="name" order_by_asc="desc" class="<?=$_order_by=="name" && $_order_by_asc=="desc"?"on":""?>">상품명<em>▼</em></a>
				</p>
			</div>
           
            <!-- 메인TABLE(s) -->
            <table class="display odd_color" cellpadding="0" cellspacing="0">
            	<colgroup>
            		<col style="width:110px;">
            		<col style="width:70px;">
            		<col>
            		<col>
<?php 
if (LoginManager::getUserLoginInfo("iam_grade") > 9) {
?>
            		<col>
<?php
}
?>            		
            		<col>
            		<col>
            		<col>
            		<col style="width:120px;">
            		<col>
            		<col>
            		<col>
            		<col>
<?php 
if (LoginManager::getUserLoginInfo("iam_grade") > 9) {
?>
            		<col style="width:50px;">
            		<col style="width:80px;">
            		<col style="width:80px;">
<?php     
}
?>
            		<col style="width:80px;">
            		<col style="width:80px;">
            		<col style="width:70px;">
            		<col style="width:100px;">
            	</colgroup>
                <thead>
                    <tr>
<?php /*                    
                        <th class="tbl_first">No</th>
                        <th>주문일시</th>
*/?>
                        <th>주문일시</th>
                        <th>주문번호</th>
                        <th>판매유형</th>
                        <th>거래처(채널)</th>
<?php 
if (LoginManager::getUserLoginInfo("iam_grade") > 9) {
?>
                        <th>원 거래처(채널)</th>
<?php
}
?>            		
                        <th>브랜드</th>
                        <th>상품코드</th>
                        <th>상품명</th>
                        <th>옵션코드</th>
                        <th>옵션명</th>
                        <th>주문번호</th>
                        <th>수량</th>
                        <th>EA</th>
<?php 
if (LoginManager::getUserLoginInfo("iam_grade") > 9) {
?>
                        <th>반영금액</th>
                        <th>공급합계</th>
                        <th>결제금액</th>
<?php     
}
?>
                        <th>판매가</th>
                        <th>상태</th>
                        <th>과/면세</th>
                        <th>작업일</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 2px solid #395467">
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
?>
                    
                    <tr>
<?php /*                    
                    	<td class="tbl_first" style="text-align:center;"><?=number_format($pg->getMaxNumOfPage() - $i)?></td>
*/?>
                        <td class="tbl_first txt_c"><?=substr($row["order_date"],0,10)." ".$arrDayOfWeek[date('w', strtotime(substr($row["order_date"],0,10)))]?></td>
                        <td class="txt_c"><?=$row["order_no"]?></td>
                        <td class="txt_c" style="<?=$row["order_type"]>"1"?"color:blue;":""?> ?>"><?=$arrSalesType[$row["order_type"]]?></td>
                        <td class="txt_c" style="<?=$row["channel"] != $row["channel_org"]?"color:green;":""?>"><?=$row["channel"]?></td>
<?php 
if (LoginManager::getUserLoginInfo("iam_grade") > 9) {
?>
                        <td class="txt_c"><?=$row["order_type"]>"1"?$row["channel"]:$row["channel_org"]?></td>
<?php
}
?>            		
                        <td class="txt_c"><?=$row["brand_name"]?></td>
                        <td><?=$row["code"]?></td>
                        <td><?=$row["name"]?></td>
                        <td><?=$row["item_code"]?></td>
                        <td><?=$row["item_name"]?></td>
                        <td class="txt_c"><?=$row["order_no"]?></td>
                        <td class="txt_r"><?=number_format($row["amount"])?></td>
                        <td class="txt_r"><?=number_format($row["ea"])?></td>
<?php 
if (LoginManager::getUserLoginInfo("iam_grade") > 9) {
?>
                        <td class="txt_c"><?=$row["fg_supply"]=="1"?"공급합계":"결제금액"?></td>
                        <td class="txt_r"><font color="<?=$row["fg_supply"]=="1"?"":"#BDBDBD"?>"><?=number_format($row["price_supply"])?></font></td>
                        <td class="txt_r"><font color="<?=$row["fg_supply"]=="1"?"#BDBDBD":""?>"><?=number_format($row["price_pay"])?></font></td>
<?php     
}
?>
                        <td class="txt_r"><?=number_format($row["price"])?></td>
                        <td class="txt_c"><?=$row["status"]?></td>
                        <td class="txt_c"><?=$row["tax_type"]?></td>
                        <td class="txt_c"><?=substr($row["reg_date"],0,10)?></td>
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="15" class="txt_c">No Data.</td></tr>
<?php
}
?>
                </tbody>
            </table>
            <!-- 메인TABLE(e) -->
			<p class="hide"><strong>Pagination</strong></p>
			<div style="position: relative;">
    			<?=$pg->getNaviForFuncGP("goPage", "<<", "<", ">", ">>")?>
<?php /*    			
    			<div style="position: absolute; right: 17px; bottom: 3px; text-align: center; line-height: 30px; border-radius: 10px; background-color: #313A3D;" class="rig_new"><a href="./goods_write.php" style="display:inline-block;padding: 5px 22px;color: #fff;">등록하기</a></div>
*/?>
    		</div>
    		
		<a href="#none" onclick="javascript:goPageTop();"  style="position: fixed; right: 31px; bottom: 31px; width: 67px; height: 67px; line-height: 70px; background-color: #313A3D; border: none; border-radius: 50%; z-index: 999; box-sizing: border-box; color: #fff; letter-spacing: .3px; text-align: center;">TOP<img src="/ism/images/common/top.png" alt="" style=" margin: -2px 0 0 2px;"/></a>
    		
<script src="/ism/cms/js/util/ValidCheck.js"></script>
<script type="text/javascript">

$(document).ready(function() {

//	getSelChannel("");
	
});

function addMonth(date, month) {
    let addMonthFirstDate = new Date(date.getFullYear(),date.getMonth() + month,1);	// month달 후의 1일
    let addMonthLastDate = new Date(addMonthFirstDate.getFullYear(),addMonthFirstDate.getMonth() + 1, 0);	// month달 후의 말일
    
    let result = addMonthFirstDate;
    if(date.getDate() > addMonthLastDate.getDate()) {
    	result.setDate(addMonthLastDate.getDate());
    } else {
    	result.setDate(date.getDate());
    }
    
    return result;
}

$(document).on("click","a[name=btnSearch]",function() {
	
	var f = document.searchForm;

    if ( VC_inValidDate(f._order_date_from, "판매일자 시작일") ) return false;
    if ( VC_inValidDate(f._order_date_to, "판매일자 종료일") ) return false;

	let arrFromDate=f._order_date_from.value.split('-');
	let arrToDate=f._order_date_to.value.split('-');
	
	let fromDate = addMonth(new Date(arrFromDate[0],arrFromDate[1]-1,arrFromDate[2]), 12);
	let toDate = new Date(arrToDate[0],arrToDate[1]-1,arrToDate[2]);
		
	if (fromDate <= toDate) {
		alert("최대 1년 단위로 조회하실 수 있습니다.    ");
		f._order_date_from.focus();
	
		return false;
	}

    f.submit();	
});

$(document).on('change','.sel_category',function() {
	var obj_select, obj_select_other;
	var next_depth = parseInt($(this).attr('depth'))+1;
	var i;

	if($("option:selected", this).val()!=="") {
	
    	obj_select = $('.sel_category[depth='+next_depth+']');
    	
    	for(i=next_depth+1;i<=4;i++) {
    		$('.sel_category[depth='+i+']').css("display","none");
    		$('.sel_category[depth='+i+'] option:eq(0)').prop("selected",true);
    	}

    	$.ajax({
    		url: "/ism/ajax/ajax_category.php",
    		data: {upper_imct_idx: $("option:selected", this).val()},
    		async: true,
    		cache: false,
    		error: function(xhr){	},
    		success: function(data){
    		
    			if(data.length > 10) {
        			obj_select.html(data);
        			
        			obj_select.css("display","inline-block");
    			} else {
    				obj_select.css("display","none");
    			}
    		}
    	});
	} else {
    	for(i=next_depth;i<=4;i++) {
    		$('.sel_category[depth='+i+']').css("display","none");
    		$('.sel_category[depth='+i+'] option:eq(0)').prop("selected",true);
    	}
	}
});

$(document).on('change','.sel_order_type',function() {
	getSelChannel($("option:selected", this).val());
});

var getSelChannel = function(order_type) {
	var obj_select

	obj_select = $('.sel_channel');

	$.ajax({
		url: "/ism/ajax/ajax_channel.php",
		data: {imst_idx: order_type},
		async: true,
		cache: false,
		error: function(xhr){	},
		success: function(data){
			obj_select.html(data);
		}
	});
}

$(document).on('click','a[name=btnExcelDownload]', function() {

	var f = document.pageForm;
	
	let arrFromDate=f._order_date_from.value.split('-');
	let arrToDate=f._order_date_to.value.split('-');
	
	let fromDate = addMonth(new Date(arrFromDate[0],arrFromDate[1]-1,arrFromDate[2]), 1);
	let toDate = new Date(arrToDate[0],arrToDate[1]-1,arrToDate[2]);

	if (fromDate <= toDate) {
		alert("엑셀 다운로드는 최대 1개월 단위로 다운로드 하실 수 있습니다.    ");
		f._order_date_from.focus();
	
		return false;
	}
	
	f.target = "_new";
	f.action = "order_list_xls.php";
	
	f.submit();
});

var goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "order_list.php";
	f.submit();
}

$(document).on('click', 'a[name=_btn_sort]', function() {
	goSort($(this).attr('order_by'), $(this).attr('order_by_asc'));
});

var goSort = function(p_order_by, p_order_by_asc) {
	var f = document.pageForm;
	f.currentPage.value = 1;
	f._order_by.value = p_order_by;
	f._order_by_asc.value = p_order_by_asc;
	f.action = "order_list.php";
	f.submit();
}

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";

@ $rs->free();
?>