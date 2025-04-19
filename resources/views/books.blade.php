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


@endsection