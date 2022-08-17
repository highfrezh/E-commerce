<?php 
use App\Models\Section;
$sections = Section::sections();
// echo "<pre>"; print_r($sections); die;
?>
<div id="header">
    <div class="container">
        <div id="welcomeLine" class="row">
            <div class="span6">Welcome!<strong> User</strong></div>
            <div class="span6">
                <div class="pull-right">
                    <input type="email" name="subscriber_email" id="subscriber_email" placeholder="Email"
                        style=" margin-top: 10px; height: 13px; width:150px;" required>
                    <button class="btn btn-mini btn-primary" onclick="addSubscriber();">Subscribe</button>&nbsp;
                    <a href="{{ url('/cart') }}"><span class="btn btn-mini btn-primary"><i
                                class="icon-shopping-cart icon-white"></i> [ <span class="totalCartItems">{{
                                totalCartItems() }} </span> ] Items in your cart
                        </span> </a>
                </div>
            </div>
        </div>
        <!-- Navbar ================================================== -->
        <section id="navbar">
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <a class="brand" href="#">Frezh Store</a>
                        <div class="nav-collapse">
                            <ul class="nav">
                                <li class="active"><a href="/">Home</a></li>
                                @foreach ($sections as $section)
                                @if(count($section['categories']) > 0)
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $section['name'] }}<b
                                            class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        @foreach ($section['categories'] as $category)
                                        <li class="divider"></li>
                                        <li class="nav-header"><a href="{{ $category['url'] }}">{{
                                                $category['category_name'] }}</a></li>
                                        @foreach ($category['subcategories'] as $subcategory)
                                        <li><a href="{{ $subcategory['url'] }}">{{ $subcategory['category_name'] }}</a>
                                        </li>
                                        @endforeach
                                        @endforeach
                                    </ul>
                                </li>
                                @endif
                                @endforeach
                                <li><a href="#">About</a></li>
                            </ul>
                            <form class="navbar-search pull-left" action="{{ url('/search-products') }}" method="GET">
                                <input type="text" name="search" class="search-query span2" placeholder="Search" />
                                <button type="submit">Go</button>
                            </form>
                            <ul class="nav pull-right">
                                @if (Auth::check())
                                <li><a href="{{ url('wishlist') }}">Wishlist</a></li>
                                <li><a href="{{ url('orders') }}">Orders</a></li>
                                @endif)
                                <li class="divider-vertical"></li>
                                @if(Auth::check())
                                <li><a href="{{ url('account') }}">My Account</a></li>
                                <li><a href="{{ url('logout') }}">Logout</a></li>
                                @else
                                <li><a href="{{ url('login-register') }}">Login / Register</a></li>
                                @endif
                            </ul>
                        </div><!-- /.nav-collapse -->
                    </div>
                </div><!-- /navbar-inner -->
            </div><!-- /navbar -->
        </section>
    </div>
</div>