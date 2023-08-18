<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Soyuz is a bootstrap 4x + laravel admin dashboard template">
    <meta name="keywords"
        content="admin, admin dashboard, admin panel, admin template, analytics, bootstrap 4, laravel, clean, crm, ecommerce, hospital, responsive, rtl, sass support, ui kits">
    <meta name="author" content="Themesbox17">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ $title ?? '' }}</title>
    <!-- Fevicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- Start css -->
    <!-- Apex css -->
    <link href="{{ asset('assets/dashboard/plugins/apexcharts/apexcharts.css') }}" rel="stylesheet">
    <!-- jvectormap css -->
    <link href="{{ asset('assets/dashboard/plugins/jvectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet">
    <!-- Slick css -->
    <link href="{{ asset('assets/dashboard/plugins/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard/plugins/slick/slick-theme.css') }}" rel="stylesheet">
    <!-- Switchery css -->
    <link href="{{ asset('assets/dashboard/plugins/switchery/switchery.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dashboard/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dashboard/css/flag-icon.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dashboard/css/style.css') }}" rel="stylesheet" type="text/css">
    {{--    <!-- DataTables css --> --}}
    {{--    <link href="{{asset('')}}assets/dashboard/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" --}}
    {{--          type="text/css"/> --}}
    {{--    <link href="{{asset('')}}assets/dashboard/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" --}}
    {{--          type="text/css"/> --}}

    <!-- End css -->
    @livewireStyles
</head>

<body class="vertical-layout">
    <!-- Start Containerbar -->
    <div id="containerbar">
        <!-- Start Leftbar -->
        <div class="leftbar">
            <!-- Start Sidebar -->
            <div class="sidebar">
                <!-- Start Navigationbar -->
                <div class="navigationbar">
                    <div class="vertical-menu-detail">
                        <div class="logobar">
                            <a href="" class="logo logo-large"><img
                                    src="{{ asset('assets/dashboard/images/logo.svg') }}" class="img-fluid"
                                    alt="logo"></a>
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-crm" role="tabpanel"
                                aria-labelledby="v-pills-crm-tab">
                                <ul class="vertical-menu">
                                    <li>
                                        <h5 class="menu-title">Rock-Gym</h5>
                                    </li>
                                    <li><a href="{{ route('admin.home') }}"><img
                                                src="{{ asset('assets/dashboard/images/svg-icon/dashboard.svg') }}"
                                                class="img-fluid" alt="dashboard">الرثيسية</a></li>
                                    <li><a href="{{ route('admin.subscribers') }}"><img
                                                src="{{ asset('assets/dashboard/images/svg-icon/dashboard.svg') }}"
                                                class="img-fluid" alt="dashboard">الشمتركين</a></li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Navigationbar -->
            </div>
            <!-- End Sidebar -->
        </div>
        <!-- End Leftbar -->
        <!-- Start Rightbar -->
        <div class="rightbar">
            <!-- Start Topbar Mobile -->
            <div class="topbar-mobile">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="mobile-logobar">
                            <a href="index.html" class="mobile-logo"><img
                                    src="{{ asset('assets/dashboard/images/logo.svg') }}" class="img-fluid"
                                    alt="logo"></a>
                        </div>
                        <div class="mobile-togglebar">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <div class="topbar-toggle-icon">
                                        <a class="topbar-toggle-hamburger" href="javascript:void();">
                                            <img src="{{ asset('assets/dashboard/images/svg-icon/horizontal.svg') }}"
                                                class="img-fluid menu-hamburger-horizontal" alt="horizontal">
                                            <img src="{{ asset('assets/dashboard/images/svg-icon/verticle.svg') }}"
                                                class="img-fluid menu-hamburger-vertical" alt="verticle">
                                        </a>
                                    </div>
                                </li>
                                <li class="list-inline-item">
                                    <div class="menubar">
                                        <a class="menu-hamburger" href="javascript:void();">
                                            <img src="{{ asset('assets/dashboard/images/svg-icon/menu.svg') }}"
                                                class="img-fluid menu-hamburger-collapse" alt="collapse">
                                            <img src="{{ asset('assets/dashboard/images/svg-icon/close.svg') }}"
                                                class="img-fluid menu-hamburger-close" alt="close">
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start Topbar -->
            <div class="topbar">
                <!-- Start row -->
                <div class="row align-items-center">
                    <!-- Start col -->
                    <div class="col-md-12 align-self-center">
                        <div class="togglebar">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <div class="menubar">
                                        <a class="menu-hamburger" href="javascript:void();">
                                            <img src="{{ asset('assets/dashboard/images/svg-icon/menu.svg') }}"
                                                class="img-fluid menu-hamburger-collapse" alt="menu">
                                            <img src="{{ asset('assets/dashboard/images/svg-icon/close.svg') }}"
                                                class="img-fluid menu-hamburger-close" alt="close">
                                        </a>
                                    </div>
                                </li>
                                <li class="list-inline-item">
                                    <div class="searchbar">
                                        <form>
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <button class="btn" type="submit"
                                                        id="button-addonSearch"><img
                                                            src="{{ asset('assets/dashboard/images/svg-icon/search.svg') }}"
                                                            class="img-fluid" alt="search"></button>
                                                </div>
                                                <input type="search" class="form-control" placeholder="Search"
                                                    aria-label="Search" aria-describedby="button-addonSearch">
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="infobar">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <div class="languagebar">
                                        <div class="dropdown">
                                            <a class="dropdown-toggle" href="#" role="button"
                                                id="languagelink" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"><span class="live-icon">EN</span><span
                                                    class="feather icon-chevron-down live-icon"></span></a>
                                            <div class="dropdown-menu" aria-labelledby="languagelink">
                                                <a class="dropdown-item" href="#"><i
                                                        class="flag flag-icon-us flag-icon-squared"></i>English</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="flag flag-icon-de flag-icon-squared"></i>German</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="flag flag-icon-bl flag-icon-squared"></i>France</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="flag flag-icon-ru flag-icon-squared"></i>Russian</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-inline-item">
                                    <div class="notifybar">
                                        <div class="dropdown">
                                            <a class="dropdown-toggle infobar-icon" href="#" role="button"
                                                id="notoficationlink" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"><img
                                                    src="{{ asset('assets/dashboard/images/svg-icon/notifications.svg') }}"
                                                    class="img-fluid" alt="notifications">
                                                <span class="live-icon">2</span></a>
                                            <div class="dropdown-menu" aria-labelledby="notoficationlink">
                                                <div class="notification-dropdown-title">
                                                    <h4>الاشعارات</h4>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-inline-item">
                                    <div class="profilebar">
                                        <div class="dropdown">
                                            <a class="dropdown-toggle" href="#" role="button"
                                                id="profilelink" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"><img
                                                    src="{{ asset('assets/dashboard/images/users/profile.svg') }}"
                                                    class="img-fluid" alt="profile"><span
                                                    class="live-icon">{{ Auth::user()->name }}</span><span
                                                    class="feather icon-chevron-down live-icon"></span></a>
                                            <div class="dropdown-menu" aria-labelledby="profilelink">
                                                <div class="dropdown-item">
                                                    <div class="profilename">
                                                        <h5>{{ Auth::user()->name }}</h5>
                                                    </div>
                                                </div>
                                                <div class="userbox">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="media dropdown-item">
                                                            <a href="#" class="profile-icon"><img
                                                                    src="{{ asset('assets/dashboard/images/svg-icon/crm.svg') }}"
                                                                    class="img-fluid" alt="user">My Profile</a>
                                                        </li>
                                                        <li class="media dropdown-item">
                                                            <a href="#" class="profile-icon"><img
                                                                    src="{{ asset('assets/dashboard/images/svg-icon/email.svg') }}"
                                                                    class="img-fluid" alt="email">Email</a>
                                                        </li>
                                                        <li class="media dropdown-item">
                                                            <a href="{{ route('logout') }}" class="profile-icon"
                                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img
                                                                    src="{{ asset('assets/dashboard/images/svg-icon/logout.svg') }}"
                                                                    class="img-fluid" alt="logout">Logout</a>
                                                            <form id="logout-form" action="{{ route('logout') }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End col -->
                </div>
                <!-- End row -->
            </div>
            <!-- End Topbar -->
            <!-- Start Breadcrumbbar -->
            <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">{{ $pageHeader ?? '' }}</h4>
                        {{--                    <div class="breadcrumb-list"> --}}
                        {{--                        <ol class="breadcrumb"> --}}
                        {{--                            <li class="breadcrumb-item"><a href="index.html">Home</a></li> --}}
                        {{--                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li> --}}
                        {{--                            <li class="breadcrumb-item active" aria-current="page">CRM</li> --}}
                        {{--                        </ol> --}}
                        {{--                    </div> --}}
                    </div>
                    <div class="col-md-4 col-lg-4">
                        {{ $action ?? '' }}
                    </div>
                </div>
            </div>
            <!-- End Breadcrumbbar -->
            <!-- Start Contentbar -->
            <div class="contentbar">

                <!-- Start row -->
                <div class="row">
                    {{ $slot }}
                </div>
            </div>
            <!-- End Contentbar -->
            <!-- Start Footerbar -->
            <div class="footerbar">
                <footer class="footer">
                    <p class="mb-0">© 2020 Soyuz - All Rights Reserved.</p>
                </footer>
            </div>
            <!-- End Footerbar -->
        </div>
        <!-- End Rightbar -->
    </div>
    <!-- End Containerbar -->
    <!-- Start js -->
    <script src="{{ asset('assets/dashboard/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/detect.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/vertical-menu.js') }}"></script>
    <!-- Switchery js -->
    <script src="{{ asset('assets/dashboard/plugins/switchery/switchery.min.js') }}"></script>
    <!-- Apex js -->
    <script src="{{ asset('assets/dashboard/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/apexcharts/irregular-data-series.js') }}"></script>
    <!-- Slick js -->
    <script src="{{ asset('assets/dashboard/plugins/slick/slick.min.js') }}"></script>
    <!-- Vector Maps js -->
    <script src="{{ asset('assets/dashboard/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- Custom Dashboard js -->
    <script src="{{ asset('assets/dashboard/js/custom/custom-dashboard.js') }}"></script>
    <!-- Core js -->
    <script src="{{ asset('assets/dashboard/js/core.js') }}"></script>
    <!-- Datatable js -->
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/dataTables.bootstrap4.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/dataTables.buttons.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/buttons.bootstrap4.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/jszip.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/pdfmake.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/vfs_fonts.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/buttons.html5.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/buttons.print.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/buttons.colVis.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/dataTables.responsive.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/plugins/datatables/responsive.bootstrap4.min.js"></script> --}}
    {{-- <script src="{{asset('')}}assets/dashboard/js/custom/custom-table-datatable.js"></script> --}}
    <!-- End js -->

    <script>
        // Livewire.on('subscriberAdded', function () {
        //     $('#addSubscriberModal').modal('hide');
        // });
        window.addEventListener('showSubscriberModal', event => {
            $('#addSubscriberModal').modal('show');
        });
        window.addEventListener('hideSubscriberModal', event => {
            $('#addSubscriberModal').modal('hide');
        });
        // deleteSubscriberModal
        window.addEventListener('deleteSubscriberModal', event => {
            $('#deleteSubscriberModal').modal('show');
        });
        window.addEventListener('subscriberDeleted', event => {
            $('#deleteSubscriberModal').modal('hide');
        });

        window.addEventListener('editSubscriberModal', event => {
            $('#editSubscriberModal').modal('show');
        });

        window.addEventListener('SubscriberUpdated', event => {
            $('#editSubscriberModal').modal('hide');
        });

        window.addEventListener('showAddSubscriptionModal', event => {
            $('#createSubscriptionModal').modal('show');
        });
        window.addEventListener('subscriptionAddedSuccessfully', event => {
            $('#createSubscriptionModal').modal('hide');
        });
        window.addEventListener('showUpdateSubscriptionModal', event => {
            $('#updateSubscriptionModal').modal('show');
        });

        window.addEventListener('subscriptionUpdatedSuccessfully', event => {
            $('#updateSubscriptionModal').modal('hide');
        });
        window.addEventListener('deleteSubscriptionModal', event => {
            $('#deleteSubscriptionModal').modal('show');
        });

        window.addEventListener('subscriptionDeleted', event => {
            $('#deleteSubscriptionModal').modal('hide');
        });

        window.addEventListener('addDiitionalDaysModal', event => {
            $('#addAdditionalDaysModal').modal('show');
        });
        window.addEventListener('additionalDaysAdded', event => {
            $('#addAdditionalDaysModal').modal('hide');
        });
        window.addEventListener('updatePaymentAmountModal', event => {
            $('#updatePaymentAmountModal').modal('show');
        });
        window.addEventListener('paymentUpdatedSuccessfully', event => {
            $('#updatePaymentAmountModal').modal('hide');
        });
    </script>

    @livewireScripts

</body>

</html>
