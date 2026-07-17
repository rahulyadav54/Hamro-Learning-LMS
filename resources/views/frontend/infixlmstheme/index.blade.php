@extends('frontend.infixlmstheme.layouts.master')
@section('title'){{getSetting()->site_title ? getSetting()->site_title : 'SONA LEARN'}} | {{__('frontendmanage.Home')}} @endsection
@section('css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    body, h1, h2, h3, h4, h5, h6, p, a, button, input, span, label {
        font-family: 'Outfit', sans-serif !important;
    }

    /* Hero Banner Redesign */
    .banner_area {
        background-size: cover !important;
        background-position: center !important;
        position: relative;
        padding: 180px 0 160px !important;
        z-index: 1;
        overflow: hidden;
    }
    .banner_area::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(26, 17, 72, 0.95) 0%, rgba(106, 79, 219, 0.8) 50%, rgba(168, 85, 247, 0.45) 100%);
        z-index: -1;
    }
    .banner_text h3 {
        font-size: 54px !important;
        font-weight: 800 !important;
        line-height: 1.25 !important;
        color: #fff !important;
        margin-bottom: 24px !important;
        letter-spacing: -1px !important;
        text-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .banner_text p {
        font-size: 18px !important;
        color: rgba(255, 255, 255, 0.85) !important;
        margin-bottom: 40px !important;
        max-width: 700px;
        line-height: 1.6;
    }
    .theme_search_field.large_search_field {
        max-width: 680px;
        background: rgba(255, 255, 255, 0.12) !important;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        border-radius: 50px !important;
        padding: 6px 10px !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
        transition: all 0.3s ease;
    }
    .theme_search_field.large_search_field:focus-within {
        background: rgba(255, 255, 255, 0.18) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        box-shadow: 0 20px 40px rgba(106, 79, 219, 0.35) !important;
    }
    .theme_search_field.large_search_field input.form-control {
        background: transparent !important;
        border: none !important;
        color: #fff !important;
        height: 52px !important;
        font-size: 16px !important;
        padding-left: 20px !important;
    }
    .theme_search_field.large_search_field input.form-control::placeholder {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    .theme_search_field.large_search_field .input-group-prepend button.btn {
        background: transparent !important;
        border: none !important;
        color: #fff !important;
        font-size: 20px !important;
        padding-left: 15px !important;
    }

    /* Feature Cards overlaying Hero */
    .couses_category {
        margin-top: -70px !important;
        position: relative;
        z-index: 99;
    }
    .single_course_cat {
        background: rgba(255, 255, 255, 0.88) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6) !important;
        border-radius: 18px !important;
        padding: 28px 24px !important;
        box-shadow: 0 15px 35px rgba(106, 79, 219, 0.08) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .single_course_cat:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 45px rgba(106, 79, 219, 0.16) !important;
        background: #fff !important;
        border-color: rgba(106, 79, 219, 0.25) !important;
    }
    .single_course_cat .icon {
        width: 56px;
        height: 56px;
        background: rgba(106, 79, 219, 0.08) !important;
        border-radius: 14px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .single_course_cat .course_content h4 {
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #1a1148 !important;
        margin-bottom: 4px !important;
    }
    .single_course_cat .course_content p {
        font-size: 13px !important;
        color: #64748b !important;
        line-height: 1.4;
    }

    /* Course Cards */
    .couse_wizged, .quiz_wizged {
        background: #fff !important;
        border-radius: 20px !important;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important;
        border: 1px solid #f1f5f9 !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .couse_wizged:hover, .quiz_wizged:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(106, 79, 219, 0.12) !important;
        border-color: rgba(106, 79, 219, 0.2) !important;
    }
    .couse_wizged .thumb, .quiz_wizged .thumb {
        border-radius: 20px 20px 0 0 !important;
        overflow: hidden;
        position: relative;
    }
    .couse_wizged .thumb_inner, .quiz_wizged .thumb_inner {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .couse_wizged:hover .thumb_inner, .quiz_wizged:hover .thumb_inner {
        transform: scale(1.1);
    }
    .couse_wizged .prise_tag, .quiz_wizged .prise_tag {
        background: linear-gradient(135deg, #6a4fdb 0%, #a855f7 100%) !important;
        box-shadow: 0 4px 12px rgba(106, 79, 219, 0.25) !important;
        border-radius: 20px !important;
        padding: 5px 12px !important;
        font-size: 13px !important;
        font-weight: 700 !important;
    }
    .couse_wizged .course_content, .quiz_wizged .course_content {
        padding: 24px 20px !important;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .couse_wizged .course_content h4, .quiz_wizged .course_content h4 {
        font-size: 18px !important;
        font-weight: 700 !important;
        line-height: 1.4 !important;
        color: #1a1148 !important;
        margin-bottom: 12px !important;
        transition: color 0.2s;
    }
    .couse_wizged:hover .course_content h4, .quiz_wizged:hover .course_content h4 {
        color: #6a4fdb !important;
    }
    .rating_cart {
        margin-bottom: 16px !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .rateing span {
        font-weight: 600 !important;
        color: #f59e0b !important;
        font-size: 14px !important;
    }
    .rateing i {
        color: #f59e0b !important;
        font-size: 13px !important;
    }
    .cart_store {
        background: rgba(106, 79, 219, 0.08) !important;
        color: #6a4fdb !important;
        width: 36px;
        height: 36px;
        border-radius: 50% !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .cart_store:hover {
        background: #6a4fdb !important;
        color: #fff !important;
        transform: scale(1.1);
    }
    .course_less_students {
        border-top: 1px solid #f1f5f9 !important;
        padding-top: 16px !important;
        margin-top: auto;
        display: flex;
        justify-content: space-between;
    }
    .course_less_students a {
        color: #94a3b8 !important;
        font-size: 13px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .course_less_students a i {
        color: #6a4fdb !important;
    }

    /* Category Widgets grid */
    .category_wiz {
        border-radius: 20px !important;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important;
        transition: all 0.3s ease;
    }
    .category_wiz:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(106, 79, 219, 0.15) !important;
    }
    .category_wiz .thumb {
        height: 220px !important;
        background-size: cover !important;
        background-position: center !important;
        position: relative;
        display: flex;
        align-items: flex-end;
        padding: 24px !important;
    }
    .category_wiz .thumb::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.1) 100%) !important;
    }
    .category_wiz .cat_btn {
        position: relative;
        z-index: 5;
        color: #fff !important;
        font-size: 22px !important;
        font-weight: 700 !important;
        background: transparent !important;
        padding: 0 !important;
        border: none !important;
    }

    /* Banner CTA Buttons */
    .theme_btn {
        background: linear-gradient(135deg, #6a4fdb 0%, #a855f7 100%) !important;
        box-shadow: 0 6px 20px rgba(106, 79, 219, 0.35) !important;
        color: #fff !important;
        border-radius: 30px !important;
        padding: 12px 36px !important;
        font-weight: 600 !important;
        border: none !important;
        transition: all 0.3s ease;
    }
    .theme_btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(106, 79, 219, 0.5) !important;
        color: #fff !important;
    }

    /* Section spacing */
    .category_area {
        padding: 100px 0 80px !important;
    }
    .section__title h3 {
        font-size: 38px !important;
        font-weight: 800 !important;
        color: #1a1148 !important;
        line-height: 1.3 !important;
    }
    /* Companies Marquee */
    .company-marquee-section {
        padding: 40px 0;
        background: #fff;
        overflow: hidden;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        margin-top: 50px;
    }
    .marquee-title {
        text-align: center;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 1.5px;
        margin-bottom: 25px;
    }
    .marquee-wrapper {
        display: flex;
        width: 100%;
        overflow: hidden;
    }
    .marquee-content {
        display: flex;
        gap: 80px;
        animation: marquee 20s linear infinite;
        white-space: nowrap;
    }
    .marquee-content span {
        font-size: 26px;
        font-weight: 800;
        color: #cbd5e1;
        letter-spacing: -0.5px;
        transition: color 0.3s;
    }
    .marquee-content span:hover {
        color: #6a4fdb;
    }
    @keyframes marquee {
        0% { transform: translateX(0%); }
        100% { transform: translateX(-50%); }
    }
</style>
@endsection
@section('js') @endsection

@section('mainContent')

    <!-- BANNER::START  -->
    <form action="{{route('search')}}">
        <div class="banner_area"
             @if(isset($homeContent->slider_banner) && !empty($homeContent->slider_banner))
             style="background-image: url('{{asset(@$homeContent->slider_banner)}}')"
            @endif>
            <div class="container">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-9 offset-lg-1">
                        <div class="banner_text">
                            <h3>{{@$homeContent->slider_title}}</h3>
                            <p>{{@$homeContent->slider_text}}</p>
                            <div class="input-group theme_search_field large_search_field">
                                <div class="input-group-prepend">
                                    <button class="btn" type="button" id="button-addon2"><i class="ti-search"></i>
                                    </button>
                                </div>
                                <input type="text" name="query" class="form-control"
                                       placeholder="{{__('frontend.Search for course, skills and Videos')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- BANNER::END  -->

    <!-- CATEGORY::START  -->
    <div class="category_area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    @if(isset($homeContent))
                        @if($homeContent->show_key_feature==1)

                            <div class="couses_category">
                                <div class="row">


                                    <div class="col-xl-4 col-md-4">
                                        <div class="single_course_cat">
                                            <div class="icon">
                                                @if(!empty($homeContent->key_feature_logo1))
                                                    <img
                                                        src="{{asset($homeContent->key_feature_logo1)}}"
                                                        alt="">
                                                @endif
                                            </div>
                                            <div class="course_content">
                                                <h4>
                                                    @if(!empty($homeContent->feature_link1))<a
                                                        href="{{$homeContent->feature_link1}}"> @endif
                                                        {{$homeContent->key_feature_title1}}
                                                        @if(!empty($homeContent->feature_link1))   </a> @endif
                                                </h4>
                                                <p>{{$homeContent->key_feature_subtitle1}} </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-4">
                                        <div class="single_course_cat">
                                            <div class="icon">
                                                @if(!empty($homeContent->key_feature_logo2))
                                                    <img
                                                        src="{{asset($homeContent->key_feature_logo2)}}"
                                                        alt="">
                                                @endif
                                            </div>
                                            <div class="course_content">
                                                <h4>
                                                    @if(!empty($homeContent->feature_link2))<a
                                                        href="{{$homeContent->feature_link2}}"> @endif
                                                        {{$homeContent->key_feature_title2}}
                                                        @if(!empty($homeContent->feature_link2))   </a> @endif
                                                </h4>
                                                <p>{{$homeContent->key_feature_subtitle2}} </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-4">
                                        <div class="single_course_cat">
                                            <div class="icon">
                                                @if(!empty($homeContent->key_feature_logo3))
                                                    <img
                                                        src="{{asset($homeContent->key_feature_logo3)}}"
                                                        alt="">
                                                @endif
                                            </div>
                                            <div class="course_content">
                                                <h4>
                                                    @if(!empty($homeContent->feature_link3))<a
                                                        href="{{$homeContent->feature_link3}}"> @endif
                                                        {{$homeContent->key_feature_title3}}
                                                        @if(!empty($homeContent->feature_link3))   </a> @endif
                                                </h4>
                                                <p>{{$homeContent->key_feature_subtitle3}} </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Row for alumni companies marquee --}}
            <div class="row">
                <div class="col-12">
                    <div class="company-marquee-section">
                        <h5 class="marquee-title">Our Alumni Work at Leading Tech Giants & Startups</h5>
                        <div class="marquee-wrapper">
                            <div class="marquee-content">
                                <span>Google</span>
                                <span>Microsoft</span>
                                <span>Meta</span>
                                <span>Amazon</span>
                                <span>Netflix</span>
                                <span>Uber</span>
                                <span>Stripe</span>
                                <span>Airbnb</span>
                                <span>Tesla</span>
                                <span>Adobe</span>
                                <span>Spotify</span>
                                {{-- Loop replication --}}
                                <span>Google</span>
                                <span>Microsoft</span>
                                <span>Meta</span>
                                <span>Amazon</span>
                                <span>Netflix</span>
                                <span>Uber</span>
                                <span>Stripe</span>
                                <span>Airbnb</span>
                                <span>Tesla</span>
                                <span>Adobe</span>
                                <span>Spotify</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section__title mb_40">
                        <h3>
                            {{@$homeContent->category_title}}
                        </h3>
                        <p>
                            {{@$homeContent->category_sub_title}}
                        </p>

                        <a href="{{route('courses')}}"
                           class="line_link">{{__('frontend.View All Courses')}}</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            @if(isset($categories))
                                @foreach ($categories as $key=>$category)
                                    @if($key==0)
                                        <div class="category_wiz mb_30">
                                            <div class="thumb cat1"
                                                 style="background-image: url('{{asset($category->thumbnail)}}')">
                                                {{--                                            <img class="w-100" src="{{asset($category->image)}}" alt="">--}}
                                                <a href="{{route('courses')}}?category={{$category->id}}"
                                                   class="cat_btn">{{$category->name}}</a>
                                            </div>
                                        </div>
                                        <a href="{{route('courses')}}"
                                           class="brouse_cat_btn ">
                                            {{__('frontend.Browse all of other categories')}}
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <div class="col-lg-6 col-md-6">
                            @if(isset($categories))
                                @foreach ($categories as $key=>$category)

                                    @if($key==1)
                                        <div class="category_wiz mb_30">
                                            <div class="thumb cat2"
                                                 style="background-image: url('{{asset($category->thumbnail)}}')">
                                                <a href="{{route('courses')}}?category={{$category->id}}"
                                                   class="cat_btn">{{$category->name}}</a>
                                            </div>
                                        </div>
                                    @elseif($key==2)
                                        <div class="category_wiz mb_30">
                                            <div class="thumb  cat3"
                                                 style="background-image: url('{{asset($category->thumbnail)}}')">
                                                <a href="{{route('courses')}}?category={{$category->id}}"
                                                   class="cat_btn">{{$category->name}}</a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CATEGORY::END  -->

    <!-- CTA::START  -->
    <div class="cta_area" style="background-image: url('{{asset(@$homeContent->instructor_banner)}}')">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-1">
                    <div class="section__title white_text">
                        <h3 class="large_title">
                            {{@$homeContent->instructor_title}}

                        </h3>
                        <p>

                            {{@$homeContent->instructor_sub_title}}
                        </p>
                        <a href="{{route('instructors')}}" class="theme_btn">{{__('frontend.Find Our Instructor')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CTA::END  -->

    <!-- COURSE::START  -->
    <div class="course_area section_spacing">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section__title text-center mb_80">
                        <h3>
                            {{@$homeContent->course_title}}


                        </h3>
                        <p>
                            {{@$homeContent->course_sub_title}}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                @if(isset($top_courses))
                    @foreach($top_courses as $c)
                        <div class="col-lg-4 col-xl-3 col-md-6">
                            <div class="couse_wizged">
                                <a href="{{route('courseDetailsView',[@$c->id,@$c->slug])}}">
                                    <div class="thumb">
                                        <div class="thumb_inner"
                                             style="background-image: url('{{ file_exists($c->thumbnail) ? asset($c->thumbnail) : asset('public/\uploads/course_sample.png') }}')">


                                        </div>
                                        <span class="prise_tag">
                                            <span>
                                                @if (@$c->discount_price!=null)
                                                    {{getPriceFormat($c->discount_price)}}
                                                @else
                                                    {{getPriceFormat($c->price)}}
                                                @endif

                                              </span>
                                        </span>
                                    </div>
                                </a>
                                <div class="course_content">
                                    <a href="{{route('courseDetailsView',[@$c->id,@$c->slug])}}">

                                        <h4 class="noBrake" title=" {{$c->title}}">
                                            {{$c->title}}
                                        </h4>
                                    </a>
                                    <div class="rating_cart">
                                        <div class="rateing">
                                            <span>{{getTotalRating($c->id)}}/5</span>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        @auth()
                                            @if(!isEnrolled($c->id,\Illuminate\Support\Facades\Auth::user()->id) && !isCart($c->id))
                                                <a href="#" class="cart_store"
                                                   data-id="{{$c->id}}">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </a>
                                            @endif
                                        @endauth
                                        @guest()
                                            @if(!isCart($c->id))
                                                <a href="#" class="cart_store"
                                                   data-id="{{$c->id}}">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </a>
                                            @endif
                                        @endguest

                                    </div>
                                    <div class="course_less_students">
                                        <a> <i class="ti-agenda"></i> {{count($c->lessons)}}
                                            {{__('frontend.Lessons')}}</a>
                                        <a>
                                            <i class="ti-user"></i> {{$c->total_enrolled}} {{__('frontend.Students')}}
                                        </a>
                                    </div>
                                </div>
                            </div>


                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-12 text-center pt_70">
                    <a href="{{route('courses')}}"
                       class="theme_btn mb_30">{{__('frontend.View All Courses')}}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- COURSE::END  -->

    <div class="package_area" style="background-image: url('{{asset(@$homeContent->best_category_banner)}}')">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9">
                    <div class="section__title text-center mb_80">
                        <h3>
                            {{@$homeContent->best_category_title}}
                        </h3>
                        <p>
                            {{@$homeContent->best_category_sub_title}}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="package_carousel_active owl-carousel">
                        @if(isset($categories))
                            @foreach($categories as $category)

                                <div class="single_package">
                                    <div class="icon">
                                        <img src="{{asset($category->image)}}" alt="">
                                    </div>
                                    <a href="{{route('courses')}}?category={{$category->id}}">
                                        <h4>{{$category->name}}</h4>
                                    </a>
                                    <p>{{$category->totalCourses()}} {{__('frontend.Courses')}}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- POPULAR_QUIZ::START  -->
    <div class="quiz_area">
        <div class="container">
            <div class="white_box">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="section__title text-center mb_80">
                            <h3 class="mb-0">{{@$homeContent->quiz_title}}</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if(isset($top_quizzes))
                        @foreach($top_quizzes as $q)
                            <div class="col-lg-4 col-xl-3 col-md-6">
                                <div class="quiz_wizged mb_30">
                                    <a href="{{route('quizDetailsView',[@$q->id,@$q->slug])}}">
                                        <div class="thumb">
                                            <div class="thumb_inner"
                                                 style="background-image: url('{{ file_exists($q->thumbnail) ? asset($q->thumbnail) : asset('public/\uploads/course_sample.png') }}')">


                                            </div>
                                            <span class="prise_tag">
                                            <span>
                                                @if (@$q->discount_price!=null)
                                                    {{getPriceFormat($q->discount_price)}}
                                                @else
                                                    {{getPriceFormat($q->price)}}
                                                @endif

                                              </span>
                                        </span>
                                            <span class="live_quiz">Quiz</span>
                                        </div>

                                    </a>

                                    <div class="course_content">
                                        <a href="{{route('quizDetailsView',[@$q->id,@$q->slug])}}">
                                            <h4 class="noBrake" title=" {{$q->title}}">
                                                {{$q->title}}
                                            </h4>
                                        </a>
                                        <div class="rating_cart">
                                            <div class="rateing">
                                                <span>{{getTotalRating($q->id)}}/5</span>
                                                <i class="fas fa-star"></i>
                                            </div>
                                            @auth()
                                                @if(!isEnrolled($q->id,\Illuminate\Support\Facades\Auth::user()->id) && !isCart($q->id))
                                                    <a href="#" class="cart_store"
                                                       data-id="{{$q->id}}">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </a>
                                                @endif
                                            @endauth
                                            @guest()
                                                @if(!isCart($q->id))
                                                    <a href="#" class="cart_store"
                                                       data-id="{{$q->id}}">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </a>
                                                @endif
                                            @endguest
                                        </div>
                                        <div class="course_less_students">
                                            <a> <i class="ti-agenda"></i> {{count($q->quiz->assign)}}
                                                {{__('frontend.Question')}}</a>
                                            <a>
                                                <i class="ti-user"></i> {{$q->total_enrolled}} {{__('frontend.Students')}}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="row">
                    <div class="col-12 text-center pt_70">
                        <a href="{{route('quizzes')}}"
                           class="theme_btn mb_30">{{__('frontend.View All Quiz')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- POPULAR_QUIZ::END  -->

    <!-- TESTMONIAL::START  -->
    <div class="testmonial_area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section__title text-center mb_80">
                        <h3>{{@$homeContent->testimonial_title}}</h3>
                        <p>
                            {{@$homeContent->testimonial_sub_title}}

                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="testmonail_active owl-carousel">
                        @if(@$testimonials != "")
                            @foreach ($testimonials as $testimonial)
                                <div class="single_testmonial">
                                    <div class="testmonial_header d-flex align-items-center">
                                        <div class="thumb profile_info ">
                                            <div class="profile_img">
                                                <div class="testimonialImage"
                                                     style="background-image: url('{{getTestimonialImage($testimonial->image)}}')"></div>
                                            </div>

                                        </div>
                                        <div class="reviewer_name">
                                            <h4>{{@$testimonial->author}}</h4>
                                            <div class="rate d-flex align-items-center">

                                                @for($i=1;$i<=$testimonial->star;$i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor

                                            </div>
                                        </div>
                                    </div>
                                    <p> “{{@$testimonial->body}}”</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- TESTMONIAL::END  -->

    <!-- BRAND::START  -->
    <div class="brand_area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="barnd_wrapper brand_active owl-carousel">
                        @foreach($sponsors as $sponsor)
                            <div class="single_brand">
                                <img src="{{asset($sponsor->image)}}" alt="{{$sponsor->title}}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BRAND::END  -->

    <!-- BLOG::START  -->
    <div class="blog_area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="section__title text-center mb_80">
                        <h3>
                            {{@$homeContent->article_title}}
                        </h3>
                        <p>
                            {{@$homeContent->article_sub_title}}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                @if(isset($blogs))
                    @foreach($blogs as $blog)
                        <div class="col-lg-6 col-xl-3 col-md-6">

                            <div class="single_blog couse_wizged">
                                <a href="{{route('blogDetails',[$blog->id,$blog->slug])}}">
                                    <div class="thumb">
                                        <div class="thumb_inner"
                                             style="background-image: url('{{getBlogImage($blog->thumbnail)}}')">
                                        </div>

                                    </div>
                                </a>
                                <div class="blog_meta">
                                    <span>{{$blog->user->name}} . {{$blog->authored_date}}</span>
                                    <a href="{{route('blogDetails',[$blog->id,$blog->slug])}}">
                                        <h4 class="noBrake" title="{{$blog->title}}">{{$blog->title}}</h4>
                                    </a>
                                </div>
                            </div>


                        </div>
                    @endforeach
                @endif
                <div class="row col-md-12">
                    <div class="col-12 text-center pt_70">
                        <a href="{{route('blogs')}}"
                           class="theme_btn mb_30">{{__('frontend.View All Articles & News')}}</a>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!-- BLOG::END  -->


    <!-- service_cta_area::start  -->
    @if(@getSetting()->instructor_reg)
        <div class="service_cta_area">
            <div class="container">
                <div class="border_top_1px"></div>
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="row">
                            <div class="offset-3 col-lg-6 ">
                                <div class="single_cta_service mb_30">
                                    <div class="thumb">
                                        <img src="{{asset(@$homeContent->become_instructor_logo)}}" alt="">
                                    </div>
                                    <div class="cta_service_info">
                                        <h4>  {{@$homeContent->become_instructor_title}}</h4>
                                        <p>  {{@$homeContent->become_instructor_sub_title}}
                                        </p>
                                        <a href="{{route('becomeInstructor')}}"
                                           class="theme_btn small_btn">{{__('frontend.Start Teaching')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- service_cta_area::end  -->

@endsection
