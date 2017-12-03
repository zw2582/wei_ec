<?php 
use yii\helpers\Url;

?>
<header data-am-widget="header" class="am-header am-header-default header">
		  <div class="am-header-left am-header-nav">
		     <a href="#left-link" class=""> 
		       <i class="am-header-icon am-icon-angle-left"></i>
		     </a>
		  </div>
		  <h1 class="am-header-title"> <a href="#title-link" class="" style="color: #333;">厨房妈妈</a></h1>
		  <div class="am-header-right am-header-nav">
		     <a href="#right-link" class=""> </a>
		  </div>
	  </header>
	  <div class="search-input">
	  	  <input type="text" placeholder="请输入您搜索的内容" />
	  </div>
	  <ul class="paixu">
	  	<li><a href="">默认</a></li>
	  	<li><a href="">销量</a></li>
	  	<li><a href="">价格</a></li>
	  </ul>
	   <ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-default product">
		      <li>
		        <div class="am-gallery-item">
		            <a href="<?=Url::to(['/demo/detail'])?>" class="">
		              <img src="/images/p.png"  alt=""/>
		              <h3 class="am-gallery-title">商务单人餐</h3>
		              <div class="am-gallery-desc">
		              	<em>￥50</em><i class="am-icon-cart-plus"></i>
		              </div>
		            </a>
		        </div>
		      </li>
		      <li>
		        <div class="am-gallery-item">
		            <a href="<?=Url::to(['/demo/detail'])?>" class="">
		              <img src="/images/p1.png"  alt=""/>
		                <h3 class="am-gallery-title">虐狗情人杯</h3>
		                <div class="am-gallery-desc">
		                	<em>￥50</em><i class="am-icon-cart-plus"></i>
		                </div>
		            </a>
		        </div>
		      </li>
		      <li>
		        <div class="am-gallery-item">
		            <a href="<?=Url::to(['/demo/detail'])?>" class="">
		              <img src="/images/p2.png"  alt=""/>
		                <h3 class="am-gallery-title">卤香滑鸡 </h3>
		                <div class="am-gallery-desc">
		                	<em>￥50</em><i class="am-icon-cart-plus"></i>
		                </div>
		            </a>
		        </div>
		      </li>
		      <li>
		        <div class="am-gallery-item">
		            <a href="<?=Url::to(['/demo/detail'])?>" class="">
		              <img src="/images/p3.png"  alt=""/>
		                <h3 class="am-gallery-title">酷炫绵绵球</h3>
		                <div class="am-gallery-desc">
		                	<em>￥50</em><i class="am-icon-cart-plus"></i>
		                </div>
		            </a>
		        </div>
		      </li>
		 </ul>