<x-slot:pageHeader>
    الصفحة الرئيسية
</x-slot:pageHeader>
<div class="col-lg-12 col-xl-12">
    <div class="card m-b-30">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-7">
                    <h2><i class="feather icon-arrow-up text-success mr-1"></i>{{ $totalSubscribers }}</h4>
                        <p class="font-25 mb-0">اجمالي عدد المشتركين</p>
                </div>
                <div class="col-5 text-right">
                    <div class="resize-triggers">
                        <div class="expand-trigger">
                            <div style="width: 155px; height: 51px;"></div>
                        </div>
                        <div class="contract-trigger"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 col-xl-12">
    <div class="card m-b-30">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-7">
                    <h2><i class="feather icon-arrow-up text-success mr-1"></i>{{ $activeSubscribers }}</h4>
                        <p class="font-25 mb-0">الاشتراكات الفعالة</p>
                </div>
                <div class="col-5 text-right">
                    <div class="resize-triggers">
                        <div class="expand-trigger">
                            <div style="width: 155px; height: 51px;"></div>
                        </div>
                        <div class="contract-trigger"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 col-xl-12">
    <div class="card m-b-30">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-7">
                    <h2><i class="feather icon-arrow-up text-success mr-1"></i>{{ $expiredSubscribers }}</h4>
                        <p class="font-25 mb-0">الاشتراكات المنتهية</p>
                </div>
                <div class="col-5 text-right">
                    <div class="resize-triggers">
                        <div class="expand-trigger">
                            <div style="width: 155px; height: 51px;"></div>
                        </div>
                        <div class="contract-trigger"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
