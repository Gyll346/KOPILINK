@extends('layouts.app')

@section('title', 'Katalog Kopi - KOPPEE KopiLink')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Koleksi Biji Kopi</h1>
        <div class="d-inline-flex mb-lg-5">
            <p class="m-0 text-white"><a class="text-white" href="{{ route('home') }}">Home</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-white">Menu</p>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Menu Start -->
<div class="container-fluid pt-5">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Single-Origin Specialty</h4>
            <h1 class="display-4">Varietas Kopi Asal Kalimantan Barat</h1>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <h1 class="mb-5">Robusta Pasak Piang</h1>
                <div class="row align-items-center mb-5">
                    <div class="col-4 col-sm-3">
                        <img class="w-100 rounded-circle mb-3 mb-sm-0" src="{{ asset('img/menu-1.jpg') }}" alt="Robusta Natural">
                        <h5 class="menu-price">75K</h5>
                    </div>
                    <div class="col-8 col-sm-9">
                        <h4>Robusta Natural Process (250g)</h4>
                        <p class="m-0">Body tebal, aroma dark chocolate & brown sugar, keasaman rendah dengan aftertaste kacang panggang.</p>
                        <small class="text-primary font-weight-bold">Asal: Desa Pasak Piang, Kubu Raya</small>
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-4 col-sm-3">
                        <img class="w-100 rounded-circle mb-3 mb-sm-0" src="{{ asset('img/menu-2.jpg') }}" alt="Robusta Honey">
                        <h5 class="menu-price">85K</h5>
                    </div>
                    <div class="col-8 col-sm-9">
                        <h4>Robusta Honey Process (250g)</h4>
                        <p class="m-0">Sensasi manis karamel yang lembut dipadukan dengan aroma rempah kapulaga dan ketumbar tropis.</p>
                        <small class="text-primary font-weight-bold">Asal: Desa Pasak Piang, Kubu Raya</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h1 class="mb-5">Liberika Padi Jaya</h1>
                <div class="row align-items-center mb-5">
                    <div class="col-4 col-sm-3">
                        <img class="w-100 rounded-circle mb-3 mb-sm-0" src="{{ asset('img/menu-3.jpg') }}" alt="Liberika Gajah">
                        <h5 class="menu-price">95K</h5>
                    </div>
                    <div class="col-8 col-sm-9">
                        <h4>Liberika Jackfruit Single-Origin (250g)</h4>
                        <p class="m-0">Aroma nangka matang yang sangat khas, acidity floral yang elegan dan rasa manis madu hutan.</p>
                        <small class="text-primary font-weight-bold">Asal: Desa Padi Jaya, Gambut Lestari</small>
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-4 col-sm-3">
                        <img class="w-100 rounded-circle mb-3 mb-sm-0" src="{{ asset('img/menu-1.jpg') }}" alt="Liberika Anaerob">
                        <h5 class="menu-price">110K</h5>
                    </div>
                    <div class="col-8 col-sm-9">
                        <h4>Liberika Anaerobic Fermentation (250g)</h4>
                        <p class="m-0">Fermentasi anaerob khusus menghasilkan notes tropical fruits, markisa, dan sparkling aftertaste.</p>
                        <small class="text-primary font-weight-bold">Asal: Desa Padi Jaya, Gambut Lestari</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Menu End -->
@endsection
