@extends('master')
@section('content')
<div class="inner-header">
		<div class="container">
			<div class="pull-left">
				<h6 class="inner-title">Chi tiết sản phẩm<!--Sản phẩm: {{$sanpham->name}}--></h6>
			</div>
			<div class="pull-right">
				
			</div>
			<div class="clearfix"></div>
		</div>
	</div>

	<div class="container">
		<div id="content">
			<div class="row">
				<div class="col-sm-9">

					<div class="row">
						<div class="col-sm-4">
							<img src="source/image/product/{{$sanpham->image}}" alt="">
						</div>
						<div class="col-sm-8">
							<div class="single-item-body">
								<p class="single-item-title"><h2>{{$sanpham->name}}</h2></p>
								<br>
								<p class="single-item-price">
									@if($sanpham->promotion_price == 0)<!--neu sp ko khuyenmai-->
												<span class="flash-sale">{{$sanpham->price}}đ</span>
											@else<!--sp co khuyenmai-->
												<span class="flash-del">{{$sanpham->price}}đ</span>
												<span class="flash-sale">{{$sanpham->promotion_price}}đ</span>
											@endif
								</p>
							</div>

							<div class="clearfix"></div>
							<div class="space20">&nbsp;</div>

							<div class="single-item-desc">
								<p>
								</p>
							</div>
							<div class="space20">&nbsp;</div>

<!--bo chon so luong							<p>chọn số lượng:</p> -->
<p>cho vào giỏ hàng:</p>
							<div class="single-item-options">
						
								<a class="add-to-cart" href="{{route('themgiohang',$sanpham->id)}}"><i class="fa fa-shopping-cart"></i></a>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>

					<div class="space40">&nbsp;</div>
					<div class="woocommerce-tabs">
						<ul class="tabs">
							<li><a href="#tab-description">Mô tả</a></li>
						</ul>
						<div class="panel" id="tab-description">
							{{$sanpham->description}}
						</div>						
					</div>

					<div class="space40">&nbsp;</div>
					<div class="woocommerce-tabs">
						<ul class="tabs">
							<li><a href="#tab-description">Đánh giá ({{ count($item) }})</a></li>
						</ul>
						<div class="panel" id="tab-description">
							@foreach($item as $item)
							<div class="panel" id="tab-description">
								<b>{{ $item->orders->user->email }}</b>
								{{ $item->cmt }}
							</div>
							@endforeach
						</div>		

					</div>
					

					<div class="space50">&nbsp;</div>
					<div class="beta-products-list">
						<h4>Sản phẩm tương tự</h4>

						<div class="row">

							@foreach($sp_tuongtu as $sp)
                            <div class="col-lg-4 col-sm-6">
                                <div class="product-item">
                                    <div class="pi-pic">
                                        <img src="source/image/product/{{$sp->image}}" alt="">           
                                        <ul>
                                            <li class="quick-view"><a onclick="" href="{{route('chitietsanpham',$sp->id)}}">Chi tiết</a></li>
                                            <li class="w-icon"><a href="{{route('themgiohang',$sp->id)}}"><i class="fa fa-shopping-cart"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="pi-text">
                                        
                                        <a href="{{route('chitietsanpham',$sp->id)}}">
                                            <h5>{{$sp->name}}</h5>
                                        </a>
                                        <div class="product-price">
                                            {{number_format($sp->price)}}₫
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
							
						</div>
					</div> <!-- .beta-products-list -->
				</div>
				
			</div>
		</div> <!-- #content -->
	</div> <!-- .container -->
@endsection