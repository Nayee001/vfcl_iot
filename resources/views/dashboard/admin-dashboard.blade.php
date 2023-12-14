@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <!-- Ratings -->
            <div class="col-xl-3 col-sm-6 mt-5">
                <div class="card">
                    <div class="row">
                        <div class="col-8">
                            <div class="card-body">
                                <div class="card-info">
                                    <h5 class="mb-4 pb-1 text-nowrap"><b>Managers</b></h5>
                                    <div class="d-flex align-items-end mb-3">
                                        <h2 class="mb-0 me-2">{{ $managerCount }}</h2>
                                        <small class="text-success">Total</small>
                                    </div>
                                    <a class="widget-lable" href=""><i class='bx bx-list-ol'></i> View All </a>
                                    <a class="widget-lable" href=""> <i class='bx bx-plus'></i>Create New </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h-100 position-relative">
                                <img src="{{ asset('assets/img/illustrations/manager.png') }}" alt="Ratings"
                                    class="position-absolute card-img-position scaleX-n1-rtl bottom-0 w-auto end-0 me-3 me-xl-0 me-xxl-3 pe-1"
                                    width="95" height="170">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Ratings -->
            <!-- Sessions -->
            <div class="col-xl-3 col-sm-6 mt-5">
                <div class="card">
                    <div class="row">
                        <div class="col-8">
                            <div class="card-body">
                                <div class="card-info">
                                    <h5 class="mb-4 pb-1 text-nowrap"><b>Customers</b></h5>
                                    <div class="d-flex align-items-end mb-3">
                                        <h2 class="mb-0 me-2">{{ $userCount }}</h2>
                                        <small class="text-success">Total</small>
                                    </div>
                                    <a class="widget-lable" href=""><i class='bx bx-list-ol'></i> View All </a>
                                    <a class="widget-lable" href=""> <i class='bx bx-plus'></i>Create New </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h-100 position-relative">
                                <img src="{{ asset('assets/img/illustrations/users.png') }}" alt="Ratings"
                                    class="position-absolute card-img-position scaleX-n1-rtl bottom-0 w-auto end-0 me-3 me-xl-0 me-xxl-3 pe-1"
                                    width="95" height="160">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 align-self-end">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Device Types </h5>
                        <div class="dropdown">
                            <button class="btn btn-link p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-xl-2">
                            @foreach ($deviceTypesWithDeviceCount as $key => $value)
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            <div class="avatar-initial {{ getRandomBackgroundColor() }} rounded shadow">
                                                <i class='bx bx-devices'></i>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <div class="small mb-1">{{ $key }}</div>
                                            <h5 class="mb-0">{{ $value }}</h5> <small>Devices</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card h-100">
                    <div class="card-header pb-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Active Device</h5>
                            <div class="dropdown">
                                <button class="btn btn-link p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-lg-4 mt-lg-1">
                        <div class="row align-items-center">
                            <div class="col-md-6" style="position: relative;">
                                <div id="salesOverviewChart" style="min-height: 247.8px;">
                                    <div id="apexchartsdl6a5fyj"
                                        class="apexcharts-canvas apexchartsdl6a5fyj apexcharts-theme-light"
                                        style="width: 310px; height: 247.8px;"><svg id="SvgjsSvg1632" width="310"
                                            height="247.79999999999998" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                            class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                            style="background: transparent;">
                                            <g id="SvgjsG1634" class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(43, 15)">
                                                <defs id="SvgjsDefs1633">
                                                    <clipPath id="gridRectMaskdl6a5fyj">
                                                        <rect id="SvgjsRect1636" width="230" height="233" x="-2"
                                                            y="0" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMaskdl6a5fyj"></clipPath>
                                                    <clipPath id="nonForecastMaskdl6a5fyj"></clipPath>
                                                    <clipPath id="gridRectMarkerMaskdl6a5fyj">
                                                        <rect id="SvgjsRect1637" width="230" height="237" x="-2"
                                                            y="-2" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                </defs>
                                                <g id="SvgjsG1638" class="apexcharts-pie">
                                                    <g id="SvgjsG1639" transform="translate(0, 0) scale(1)">
                                                        <circle id="SvgjsCircle1640" r="74.37073170731708" cx="113"
                                                            cy="113" fill="transparent"></circle>
                                                        <g id="SvgjsG1641" class="apexcharts-slices">
                                                            <g id="SvgjsG1642"
                                                                class="apexcharts-series apexcharts-pie-series"
                                                                seriesName="Apparel" rel="1" data:realIndex="0">
                                                                <path id="SvgjsPath1643"
                                                                    d="M 113 6.756097560975604 A 106.2439024390244 106.2439024390244 0 0 1 185.7289559372041 35.55152826713004 L 163.9102691560429 58.78606978699103 A 74.37073170731708 74.37073170731708 0 0 0 113 38.62926829268292 L 113 6.756097560975604 z"
                                                                    fill="rgba(144,85,253,1)" fill-opacity="1"
                                                                    stroke-opacity="1" stroke-linecap="butt"
                                                                    stroke-width="0" stroke-dasharray="0"
                                                                    class="apexcharts-pie-area apexcharts-donut-slice-0"
                                                                    index="0" j="0" data:angle="43.2"
                                                                    data:startAngle="0" data:strokeWidth="0"
                                                                    data:value="12"
                                                                    data:pathOrig="M 113 6.756097560975604 A 106.2439024390244 106.2439024390244 0 0 1 185.7289559372041 35.55152826713004 L 163.9102691560429 58.78606978699103 A 74.37073170731708 74.37073170731708 0 0 0 113 38.62926829268292 L 113 6.756097560975604 z">
                                                                </path>
                                                            </g>
                                                            <g id="SvgjsG1644"
                                                                class="apexcharts-series apexcharts-pie-series"
                                                                seriesName="Electronics" rel="2"
                                                                data:realIndex="1">
                                                                <path id="SvgjsPath1645"
                                                                    d="M 185.7289559372041 35.55152826713004 A 106.2439024390244 106.2439024390244 0 0 1 190.44847173287 185.7289559372041 L 167.21393021300898 163.91026915604286 A 74.37073170731708 74.37073170731708 0 0 0 163.9102691560429 58.78606978699103 L 185.7289559372041 35.55152826713004 z"
                                                                    fill="#9055fdb3" fill-opacity="1" stroke-opacity="1"
                                                                    stroke-linecap="butt" stroke-width="0"
                                                                    stroke-dasharray="0"
                                                                    class="apexcharts-pie-area apexcharts-donut-slice-1"
                                                                    index="0" j="1" data:angle="89.99999999999999"
                                                                    data:startAngle="43.2" data:strokeWidth="0"
                                                                    data:value="25"
                                                                    data:pathOrig="M 185.7289559372041 35.55152826713004 A 106.2439024390244 106.2439024390244 0 0 1 190.44847173287 185.7289559372041 L 167.21393021300898 163.91026915604286 A 74.37073170731708 74.37073170731708 0 0 0 163.9102691560429 58.78606978699103 L 185.7289559372041 35.55152826713004 z">
                                                                </path>
                                                            </g>
                                                            <g id="SvgjsG1646"
                                                                class="apexcharts-series apexcharts-pie-series"
                                                                seriesName="FMCG" rel="3" data:realIndex="2">
                                                                <path id="SvgjsPath1647"
                                                                    d="M 190.44847173287 185.7289559372041 A 106.2439024390244 106.2439024390244 0 0 1 113 219.2439024390244 L 113 187.37073170731708 A 74.37073170731708 74.37073170731708 0 0 0 167.21393021300898 163.91026915604286 L 190.44847173287 185.7289559372041 z"
                                                                    fill="#9055fd80" fill-opacity="1" stroke-opacity="1"
                                                                    stroke-linecap="butt" stroke-width="0"
                                                                    stroke-dasharray="0"
                                                                    class="apexcharts-pie-area apexcharts-donut-slice-2"
                                                                    index="0" j="2" data:angle="46.80000000000001"
                                                                    data:startAngle="133.2" data:strokeWidth="0"
                                                                    data:value="13"
                                                                    data:pathOrig="M 190.44847173287 185.7289559372041 A 106.2439024390244 106.2439024390244 0 0 1 113 219.2439024390244 L 113 187.37073170731708 A 74.37073170731708 74.37073170731708 0 0 0 167.21393021300898 163.91026915604286 L 190.44847173287 185.7289559372041 z">
                                                                </path>
                                                            </g>
                                                            <g id="SvgjsG1648"
                                                                class="apexcharts-series apexcharts-pie-series"
                                                                seriesName="OtherxSales" rel="4"
                                                                data:realIndex="3">
                                                                <path id="SvgjsPath1649"
                                                                    d="M 113 219.2439024390244 A 106.2439024390244 106.2439024390244 0 0 1 112.98145694101684 6.75609917916276 L 112.98701985871179 38.62926942541394 A 74.37073170731708 74.37073170731708 0 0 0 113 187.37073170731708 L 113 219.2439024390244 z"
                                                                    fill="rgba(240,242,248,1)" fill-opacity="1"
                                                                    stroke-opacity="1" stroke-linecap="butt"
                                                                    stroke-width="0" stroke-dasharray="0"
                                                                    class="apexcharts-pie-area apexcharts-donut-slice-3"
                                                                    index="0" j="3" data:angle="180"
                                                                    data:startAngle="180" data:strokeWidth="0"
                                                                    data:value="50"
                                                                    data:pathOrig="M 113 219.2439024390244 A 106.2439024390244 106.2439024390244 0 0 1 112.98145694101684 6.75609917916276 L 112.98701985871179 38.62926942541394 A 74.37073170731708 74.37073170731708 0 0 0 113 187.37073170731708 L 113 219.2439024390244 z">
                                                                </path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g id="SvgjsG1650" class="apexcharts-datalabels-group"
                                                        transform="translate(0, 0) scale(1)"><text id="SvgjsText1651"
                                                            font-family="Inter" x="113" y="133" text-anchor="middle"
                                                            dominant-baseline="auto" font-size=".7rem" font-weight="400"
                                                            fill="#89868d"
                                                            class="apexcharts-text apexcharts-datalabel-label"
                                                            style="font-family: Inter;">Weekly Vsits</text><text
                                                            id="SvgjsText1652" font-family="Inter" x="113" y="109"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="26px" font-weight="500" fill="#544f5a"
                                                            class="apexcharts-text apexcharts-datalabel-value"
                                                            style="font-family: Inter;">102k</text></g>
                                                </g>
                                                <line id="SvgjsLine1653" x1="0" y1="0" x2="226"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line id="SvgjsLine1654" x1="0" y1="0" x2="226"
                                                    y2="0" stroke-dasharray="0" stroke-width="0"
                                                    stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                            </g>
                                            <g id="SvgjsG1635" class="apexcharts-annotations"></g>
                                        </svg>
                                        <div class="apexcharts-legend"></div>
                                        <div class="apexcharts-tooltip apexcharts-theme-dark">
                                            <div class="apexcharts-tooltip-series-group" style="order: 1;"><span
                                                    class="apexcharts-tooltip-marker"
                                                    style="background-color: rgb(144, 85, 253);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                            <div class="apexcharts-tooltip-series-group" style="order: 2;"><span
                                                    class="apexcharts-tooltip-marker"
                                                    style="background-color: rgba(144, 85, 253, 0.7);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                            <div class="apexcharts-tooltip-series-group" style="order: 3;"><span
                                                    class="apexcharts-tooltip-marker"
                                                    style="background-color: rgba(144, 85, 253, 0.5);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                            <div class="apexcharts-tooltip-series-group" style="order: 4;"><span
                                                    class="apexcharts-tooltip-marker"
                                                    style="background-color: rgb(240, 242, 248);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 335px; height: 249px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar">
                                        <div class="avatar-initial {{ getRandomBackgroundColor() }} rounded shadow">
                                            <i class='bx bx-devices'></i>
                                        </div>
                                    </div>
                                    <div class="ms-3 d-flex flex-column">
                                        <p class="mb-1">Device Name</p>
                                        <h5 class="mb-1">Status</h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="row g-4">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="badge badge-dot bg-primary me-2"></div>
                                            <p class="text-heading mb-0">Apparel</p>
                                        </div>
                                        <p class="fw-medium mb-0">$12,150</p>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="badge badge-dot bg-primary me-2"></div>
                                            <p class="text-heading mb-0">Electronic</p>
                                        </div>
                                        <p class="fw-medium mb-0">$24,900</p>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="badge badge-dot bg-primary me-2"></div>
                                            <p class="text-heading mb-0">FMCG</p>
                                        </div>
                                        <p class="fw-medium mb-0">$12,750</p>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="badge badge-dot bg-primary me-2"></div>
                                            <p class="text-heading mb-0">Other Sales</p>
                                        </div>
                                        <p class="fw-medium mb-0">$50,200</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 ">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Devices Statistic</h5>
                        <div class="dropdown">
                            <button class="btn btn-link p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-3" style="position: relative;">
                            <div class="mb-2">
                                <h5 class="display-3 mb-0 item-">{{ $deviceCount }}</h5>
                                <small>Total Devices</small>
                            </div>
                            <div class="mb-2">
                                <h5 class="display-3 mb-0" id="dataCount">Loading...</h5>
                                <small>Total Data Recieved</small>
                            </div>
                            <div class="resize-triggers">
                                <div class="expand-trigger">
                                    <div style="width: 409px; height: 100px;"></div>
                                </div>
                                <div class="contract-trigger"></div>
                            </div>
                        </div>
                        <div id="deviceDataContainer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection

@include('dashboard.admin-dashboard-js')
