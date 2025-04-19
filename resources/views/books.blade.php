<style>
    
</style>
@extends('layouts.app')

@section('content')

<section id="billboard">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <button class="prev slick-arrow">
                    <i class="icon icon-arrow-left"></i>
                </button>

                <div class="main-slider pattern-overlay">
                    @foreach ($sliderBooks as $book)
    <div class="slider-item">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="banner-content">
                    <h2 class="banner-title">{{ $book->title }}</h2>
                    <p>{{ Str::limit($book->description, 150) }}</p>
                    <div class="btn-wrap">
                        <a href="#" class="btn btn-outline-accent btn-accent-arrow">
                            Read More <i class="icon icon-ns-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="{{ $book->cover_image ?? asset('images/default-cover.jpg') }}" alt="banner" class="banner-image img-fluid">
            </div>
        </div>
    </div>
@endforeach

                </div>

                <button class="next slick-arrow">
                    <i class="icon icon-arrow-right"></i>
                </button>

            </div>
        </div>
    </div>
</section>

<section id="popular-books" class="bookshelf py-5 my-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header align-center">
                    <div class="title">
                        <span>Some quality items</span>
                    </div>
                    <h2 class="section-title">Popular Books</h2>
                </div>

                <ul class="tabs">
                    <!-- Tab "All Books" sebagai tab default -->
                    <li data-tab-target="#all-genre" class="active tab">All Books</li>
                    @foreach ($categories as $category)
                    <li data-tab-target="#{{ Str::slug($category->name) }}" class="tab">{{ $category->name }}</li>
                    @endforeach
                </ul>

                <!-- All Books (default tab) -->
                <div id="all-genre" data-tab-content class="row">
                    <h3>All Books</h3> <!-- Title for All Books -->
                    @foreach ($books as $book)
                    <div class="col-md-3">
                        <div class="product-item">
                            <figure class="product-style">
                                <img src="{{ $book->cover_image }}" alt="Books" class="product-item">
                                <button type="button" class="add-to-cart" data-product-tile="add-to-cart">Beli Sekarang</button>
                            </figure>
                            <figcaption>
                                <h3>{{ $book->title }}</h3>
                                <span>{{ $book->author }}</span>
                                <div class="item-price">Rp{{ number_format($book->price) }}</div>
                            </figcaption>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Genre Specific Books -->
                @foreach ($categories as $category)
                <div id="{{ Str::slug($category->name) }}" data-tab-content class="row">
                    <h3>{{ $category->name }}</h3> <!-- Title for Category -->
                    @foreach ($books->where('category_id', $category->id) as $book)
                    <div class="col-md-3">
                        <div class="product-item">
                            <figure class="product-style">
                                <img src="{{ $book->cover_image }}" alt="Books" class="product-item">
                                <button type="button" class="add-to-cart" data-product-tile="add-to-cart">Beli Sekarang</button>
                            </figure>
                            <figcaption>
                                <h3>{{ $book->title }}</h3>
                                <span>{{ $book->author }}</span>
                                <div class="item-price">Rp{{ number_format($book->price) }}</div>
                            </figcaption>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach

            </div><!--inner-tabs-->
        </div>
    </div>
</section>





@endsection