{{-- data-toggle="modal" data-target="#addSubscriberModal" --}}
<x-slot name="action">
    <div class="widgetbar">
        <button data-toggle="modal" data-target="#addSubscriberModal" class="btn btn-primary-rgba"><i
                class="feather icon-plus mr-2"></i>اضافة مشترك
        </button>
    </div>
</x-slot>
<x-slot:pageHeader>
    المشتركون
</x-slot:pageHeader>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <h5 class="card-title">المشتركون</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table
                    class="table foo-filtering-table footable footable-2 footable-filtering footable-filtering-right breakpoint-lg"
                    data-filtering="true" style="">

                    <thead>
                        <tr>
                            <th colspan="2">
                                <div class="form-group">
                                    <label for="status-filter" class="mr-1">فلترة حسب:</label>
                                    <select class="form-control" wire:model="statusFilter" id="status-filter">
                                        <option value="all">الكل</option>
                                        <option value="active">الاشتراكات الفعالة</option>
                                        <option value="expired">الاشتراكات المنتهية</option>
                                    </select>
                                </div>
                            </th>
                            <th colspan="3">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="البحث"
                                        wire:model.debounce.100ms="search">
                                </div>
                            </th>
                        </tr>
                        <tr class="footable-header">
                            <th data-breakpoints="xs" class="footable-first-visible" style="display: table-cell;">الرقم
                            </th>
                            <th style="display: table-cell;">الاسم</th>
                            <th style="display: table-cell;">الجوال</th>
                            <th data-breakpoints="xs" style="display: table-cell;">الصورة</th>
                            <th data-breakpoints="xs sm" style="display: table-cell;">تاريخ التسجيل</th>
                            <th data-breakpoints="xs sm md" style="display: table-cell;">تاريخ الانتهاء</th>
                            <th data-breakpoints="xs sm md" style="display: table-cell;">حالة الدفع</th>

                            <th data-breakpoints="xs sm md" style="display: table-cell;">المدة المتبقية</th>
                            <th data-breakpoints="xs sm md" style="display: table-cell;">حالة الاشتراك</th>
                            <th data-type="html" data-breakpoints="xs sm md" class="footable-last-visible"
                                style="display: table-cell;">الأوامر
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscribers as $key => $subscriber)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $subscriber->name }}</td>
                                <td>{{ $subscriber->phone }}</td>
                                <td><img src="{{ asset('storage/subscribers/' . $subscriber->image) }}" width="60px"
                                        height="60px">
                                </td>
                                <td>
                                    @if ($subscriber->subscription)
                                        {{ \Carbon\Carbon::parse($subscriber->subscription->start_date)->format('Y-m-d') ?? '' }}
                                    @else
                                    @endif

                                </td>
                                <td>
                                    @if ($subscriber->subscription)
                                        {{ \Carbon\Carbon::parse($subscriber->subscription->end_date)->format('Y-m-d') ?? '' }}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if ($subscriber->subscription)
                                        @if ($subscriber->subscription->payment_status == 'full')
                                            <span class="badge badge-pill badge-success">تم الدفع كامل</span>
                                        @elseif($subscriber->subscription->payment_status == 'partial')
                                            <span class="badge badge-pill badge-warning"
                                                wire:click="updatePaymentAmountModal({{ $subscriber->id }})"> متبقي
                                                {{ $subscriber->subscription->remaining_payment }}
                                                شيكل
                                            </span>
                                        @else
                                            <span class="badge badge-pill badge-danger"
                                                wire:click="updatePaymentAmountModal({{ $subscriber->id }})">لم يتم
                                                الدقع</span>
                                        @endif
                                    @else
                                    @endif
                                </td>

                                <td>
                                    @if ($subscriber->subscription)
                                        متبقي {{ $subscriber->subscription->remaining_duration['days'] }} يوم
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if ($subscriber->subscription)
                                        @if ($subscriber->subscription->status == 'active')
                                            <span class="badge badge-success">فعال</span>
                                        @elseif ($subscriber->subscription->status == 'expired')
                                            <span class="badge badge-danger">منتهي</span>
                                        @else
                                            <span class="badge badge-primary">{{ $subscriber->subscription->status }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary">عير مشترك </span>
                                    @endif
                                </td>

                                <td class="row">
                                    <button wire:click="deleteSubscriberModal({{ $subscriber->id }})" type="button"
                                        class="btn btn-round btn-danger-rgba"><i class="feather icon-trash-2"></i>
                                    </button>
                                    <button wire:click="editSubscriberModal({{ $subscriber->id }})" type="button"
                                        class="btn btn-round btn-warning-rgba"><i class="feather icon-upload"></i>
                                    </button>
                                    @if ($subscriber->subscription)
                                        @if ($subscriber->subscription->status == 'active')
                                            <!-- Hide the "Subscribe" button -->
                                        @elseif ($subscriber->subscription->status == 'expired')
                                            <button wire:click="updateSubscriptionModal({{ $subscriber->id }})"
                                                type="button" class="btn btn-rounded btn-primary-rgba">تجديد
                                            </button>
                                        @endif
                                    @else
                                        <button wire:click="createSubscriptionModal({{ $subscriber->id }})"
                                            type="button" class="btn btn-round btn-success"><i
                                                class="feather icon-plus"></i></button>
                                    @endif
                                    @if ($subscriber->subscription && $subscriber->subscription->status == 'active')
                                        <div class="btn-group mr-2">
                                            <div class="dropdown show">
                                                <button class="btn btn-round btn-primary-rgba " type="button"
                                                    id="CustomdropdownMenuButton3" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="true"><i
                                                        class="feather icon-more-vertical-"></i></button>
                                                <div class="dropdown-menu" aria-labelledby="CustomdropdownMenuButton3"
                                                    x-placement="bottom-start"
                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 40px, 0px);">
                                                    <button
                                                        wire:click="deleteSubscriptionModal({{ $subscriber->subscription->id ?? '' }})"
                                                        class="dropdown-item">
                                                        حذف الاشتراك
                                                    </button>
                                                    <button wire:click="addAdditionalDaysModal({{ $subscriber->id }})"
                                                        class="dropdown-item">
                                                        زيادة مدة الاشتراك
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                <nav>
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($subscribers->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item"><a class="page-link" wire:click="previousPage"
                                    href="javascript:void(0)">&laquo;</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($subscribers->getUrlRange(1, $subscribers->lastPage()) as $page => $url)
                            @if ($page == $subscribers->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link"
                                        wire:click="gotoPage({{ $page }})"
                                        href="javascript:void(0)">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($subscribers->hasMorePages())
                            <li class="page-item"><a class="page-link" wire:click="nextPage"
                                    href="javascript:void(0)">&raquo;</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif
                    </ul>
                </nav>

            </div>
        </div>
    </div>
    {{--    <div class="card m-b-30"> --}}
    {{--        <div class="card-header"> --}}
    {{--            <h5 class="card-title">المشتركون</h5> --}}
    {{--        </div> --}}
    {{--        <div> --}}
    {{--            <input type="text" wire:model.debounce.100ms="search" placeholder="Search by name"> --}}
    {{--        </div> --}}
    {{--        <div class="card-body"> --}}
    {{--            <div class="table-responsive"> --}}
    {{--                <table id="default-datatable" class="display table table-striped table-bordered"> --}}
    {{--                    <thead> --}}
    {{--                    <tr> --}}
    {{--                        <th scope="col">الرقم</th> --}}
    {{--                        <th scope="col">الاسم</th> --}}
    {{--                        <th scope="col">الجوال</th> --}}
    {{--                        <th scope="col">الصورة</th> --}}
    {{--                        <th scope="col">تاريخ التسجيل</th> --}}
    {{--                        <th scope="col">تاريخ الانتهاء</th> --}}
    {{--                        <th scope="col"> المدة المتبقية</th> --}}
    {{--                        <th scope="col">حالة الاشتراك</th> --}}
    {{--                        <th scope="col">الأوامر</th> --}}
    {{--                    </tr> --}}
    {{--                    </thead> --}}
    {{--                    <tbody> --}}
    {{--                    @foreach ($subscribers as $key => $subscriber) --}}
    {{--                        <tr> --}}
    {{--                            <th scope="row">{{ $key + 1 }}</th> --}}
    {{--                            <td>{{ $subscriber->name }}</td> --}}
    {{--                            <td>{{ $subscriber->phone }}</td> --}}
    {{--                            <td><img src="{{ asset('storage/subscribers/' .$subscriber->image) }}" width="60px" --}}
    {{--                                     height="60px"> --}}
    {{--                            </td> --}}
    {{--                            <td> --}}
    {{--                                @if ($subscriber->subscription) --}}
    {{--                                    {{ \Carbon\Carbon::parse($subscriber->subscription->start_date)->format('Y-m-d')??'' }} --}}
    {{--                                @else --}}

    {{--                                @endif --}}

    {{--                            </td> --}}
    {{--                            <td> --}}
    {{--                                @if ($subscriber->subscription) --}}
    {{--                                    {{ \Carbon\Carbon::parse($subscriber->subscription->end_date)->format('Y-m-d')??'' }} --}}
    {{--                                @else --}}
    {{--                                @endif --}}
    {{--                            </td> --}}
    {{--                            <td> --}}
    {{--                                @if ($subscriber->subscription) --}}
    {{--                                    متبقي {{ $subscriber->subscription->remaining_duration['days'] }}  يوم --}}
    {{--                                @else --}}
    {{--                                @endif --}}
    {{--                            </td> --}}
    {{--                            <td> --}}
    {{--                                @if ($subscriber->subscription) --}}
    {{--                                    @if ($subscriber->subscription->status == 'active') --}}
    {{--                                        <span class="badge badge-success">فعال</span> --}}
    {{--                                    @elseif ($subscriber->subscription->status == 'expired') --}}
    {{--                                        <span class="badge badge-danger">منتهي</span> --}}
    {{--                                    @else --}}
    {{--                                        <span --}}
    {{--                                            class="badge badge-primary">{{ $subscriber->subscription->status }} --}}
    {{--                                        </span> --}}
    {{--                                    @endif --}}
    {{--                                @else --}}
    {{--                                    <span class="badge badge-secondary">عير مشترك </span> --}}
    {{--                                @endif --}}
    {{--                            </td> --}}

    {{--                            <td class="row"> --}}
    {{--                                <button wire:click="deleteSubscriberModal({{$subscriber->id}})" type="button" --}}
    {{--                                        class="btn btn-round btn-danger-rgba"><i class="feather icon-trash-2"></i> --}}
    {{--                                </button> --}}
    {{--                                <button wire:click="editSubscriberModal({{$subscriber->id}})" type="button" --}}
    {{--                                        class="btn btn-round btn-warning-rgba"><i class="feather icon-upload"></i> --}}
    {{--                                </button> --}}
    {{--                                @if ($subscriber->subscription) --}}
    {{--                                    @if ($subscriber->subscription->status == 'active') --}}
    {{--                                        <!-- Hide the "Subscribe" button --> --}}
    {{--                                    @elseif ($subscriber->subscription->status == 'expired') --}}
    {{--                                        <button wire:click="updateSubscriptionModal({{$subscriber->id}})" type="button" --}}
    {{--                                                class="btn btn-rounded btn-primary-rgba">تجديد --}}
    {{--                                        </button> --}}
    {{--                                    @endif --}}
    {{--                                @else --}}
    {{--                                    <button wire:click="createSubscriptionModal({{$subscriber->id}})" type="button" --}}
    {{--                                            class="btn btn-round btn-success"><i --}}
    {{--                                            class="feather icon-plus"></i></button> --}}
    {{--                                @endif --}}
    {{--                                @if ($subscriber->subscription && $subscriber->subscription->status == 'active') --}}
    {{--                                    <div class="btn-group mr-2"> --}}
    {{--                                        <div class="dropdown show"> --}}
    {{--                                            <button class="btn btn-round btn-primary-rgba " type="button" --}}
    {{--                                                    id="CustomdropdownMenuButton3" data-toggle="dropdown" --}}
    {{--                                                    aria-haspopup="true" aria-expanded="true"><i --}}
    {{--                                                    class="feather icon-more-vertical-"></i></button> --}}
    {{--                                            <div class="dropdown-menu" --}}
    {{--                                                 aria-labelledby="CustomdropdownMenuButton3" --}}
    {{--                                                 x-placement="bottom-start" --}}
    {{--                                                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 40px, 0px);"> --}}
    {{--                                                <button --}}
    {{--                                                    wire:click="deleteSubscriptionModal({{$subscriber->subscription->id ??''}})" --}}
    {{--                                                    class="dropdown-item"> --}}
    {{--                                                    حذف الاشتراك --}}
    {{--                                                </button> --}}
    {{--                                                </form> --}}
    {{--                                            </div> --}}
    {{--                                        </div> --}}
    {{--                                    </div> --}}
    {{--                                @endif --}}
    {{--                            </td> --}}
    {{--                        </tr> --}}
    {{--                    @endforeach --}}
    {{--                    </tbody> --}}
    {{--                </table> --}}
    {{--            </div> --}}
    {{--        </div> --}}
    {{--    </div> --}}
    {{--    Add Subscriber Modal --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="addSubscriberModal" tabindex="-1"
        role="dialog" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">اضافة مشترك</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeSubscriber" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input wire:model.defer="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" id="name"
                                aria-describedby="emailHelp" placeholder="اسم المشترك">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">رقم الجوال</label>
                            <input wire:model.defer="phone" type="number"
                                class="form-control @error('name') is-invalid @enderror" id="phone"
                                placeholder="رقم جوال المشترك">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image">الصورة</label>
                            <input wire:model="image" type="file" class="form-control" id="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                wire:click="closeModal">اغلاق
                            </button>
                            <button type="submit" class="btn btn-primary">تأكيد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    Edit Subscriber Modal --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="editSubscriberModal" tabindex="-1"
        role="dialog" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel"> تعديل بيانات المشترك </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="editSubscriber" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input wire:model.defer="editSubscriberName" type="text"
                                class="form-control @error('name') is-invalid @enderror" id="name"
                                aria-describedby="emailHelp" placeholder="Enter name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input wire:model.defer="editSubscriberPhone" type="number"
                                class="form-control @error('name') is-invalid @enderror" id="phone"
                                placeholder="Phone">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input wire:model="image" type="file" class="form-control file" id="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                wire:click="closeModal">Close
                            </button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    Delete Subscriber Modal --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="deleteSubscriberModal" tabindex="-1"
        role="dialog" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">حذف مشترك</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>هل تريد حذف المشترك؟ </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق
                    </button>
                    <button wire:click="deleteSubscriber" class="btn btn-primary"> حذف المشترك
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{--    Add Subscription Modal --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="createSubscriptionModal" tabindex="-1"
        role="dialog" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">
                        اضافة اشتراك </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeSubscription">
                        <div class="form-group">
                            <input type="hidden" wire:model="subscriberId" readonly>
                        </div>
                        <div class="form-group">
                            <label for="subscriptionType">نوع الاشتراك:</label>
                            <select wire:model="subscriptionType" class="form-control" id="subscriptionType">
                                <option value="" selected>اختر نوع الاشتراك</option>
                                <option value="specified">بالأشهر:</option>
                                <option value="custom">مخصص:</option>
                            </select>
                        </div>
                        @if ($subscriptionType === 'specified')
                            <div class="form-group">
                                <label for="duration">المدة</label>
                                <select wire:model="duration" class="form-control" id="duration">
                                    <option selected>اختر المدة</option>
                                    <option value="1">شهر</option>
                                    <option value="2">شهرين</option>
                                    <option value="3">3 أشهر</option>
                                    <option value="4">4 أشهر</option>
                                    <option value="5">5 أشهر</option>
                                    <option value="6">6 أشهر</option>
                                    <option value="7">7 أشهر</option>
                                    <option value="8">8 أشهر</option>
                                    <option value="9">9 أشهر</option>
                                    <option value="10">10 أشهر</option>
                                    <option value="11">11 شهر</option>
                                    <option value="12"> سنة</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                        @elseif ($subscriptionType === 'custom')
                            <div class="form-group">
                                <label for="customStartDate">تاريخ الاشتراك:</label>
                                <input type="date" wire:model="customStartDate" class="form-control"
                                    id="customStartDate">
                            </div>
                            <div class="form-group">
                                <label for="customEndDate">تاريخ نهاية الاشتراك:</label>
                                <input type="date" wire:model="customEndDate" class="form-control"
                                    id="customEndDate">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="paymentAmount">الدفع</label>
                            <input wire:model.defer="paymentAmount" type="number"
                                class="form-control @error('name') is-invalid @enderror" id="paymentAmount"
                                placeholder="الدفع">
                            @error('paymentAmount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                            <button type="submit" class="btn btn-primary">تجديد الاشتراك</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    Renew Subscription Modal --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="updateSubscriptionModal" tabindex="-1"
        role="dialog" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">
                        تجديد الاشتراك للمشترك: {{ $subscriber->name ?? '' }} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateSubscription">
                        <div class="form-group">
                            <input type="hidden" wire:model="subscriberId" readonly>
                        </div>
                        <div class="form-group">
                            <label for="subscriptionType">نوع الاشتراك:</label>
                            <select wire:model="subscriptionType" class="form-control" id="subscriptionType">
                                <option value="" selected>اختر نوع الاشتراك</option>
                                <option value="specified">بالأشهر:</option>
                                <option value="custom">مخصص:</option>
                            </select>
                        </div>
                        @if ($subscriptionType === 'specified')
                            <div class="form-group">
                                <label for="duration">المدة</label>
                                <select wire:model="duration" class="form-control" id="duration">
                                    <option selected>اختر المدة</option>
                                    <option value="1">شهر</option>
                                    <option value="2">شهرين</option>
                                    <option value="3">3 أشهر</option>
                                    <option value="4">4 أشهر</option>
                                    <option value="5">5 أشهر</option>
                                    <option value="6">6 أشهر</option>
                                    <option value="7">7 أشهر</option>
                                    <option value="8">8 أشهر</option>
                                    <option value="9">9 أشهر</option>
                                    <option value="10">10 أشهر</option>
                                    <option value="11">11 شهر</option>
                                    <option value="12"> سنة</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                        @elseif ($subscriptionType === 'custom')
                            <div class="form-group">
                                <label for="customStartDate">تاريخ الاشتراك:</label>
                                <input type="date" wire:model="customStartDate" class="form-control"
                                    id="customStartDate">
                            </div>
                            <div class="form-group">
                                <label for="customEndDate">تاريخ نهاية الاشتراك:</label>
                                <input type="date" wire:model="customEndDate" class="form-control"
                                    id="customEndDate">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="updatedPayment">الدفع</label>
                            <input wire:model.defer="updatedPayment" type="number"
                                class="form-control @error('name') is-invalid @enderror" id="updatedPayment"
                                placeholder="الدفع">
                            @error('updatedPayment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                            <button type="submit" class="btn btn-primary">تجديد الاشتراك</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    Delete Subscription Modal --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="deleteSubscriptionModal" tabindex="-1"
        role="dialog" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">حذف الاشتراك للمشترك:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>هل تريد حذف الاشتراك؟ </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق
                    </button>
                    <button wire:click="deleteSubscription" class="btn btn-primary"> حذف الاشتراك
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{--    Add Addtional Days Modal --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="addAdditionalDaysModal" tabindex="-1"
        role="dialog" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">زيادة مدة الاشتراك:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="addAdditionalDays">
                        <div class="form-group">
                            <label for="additionalDays">زيادة مدة الاشتراك (بالأيام):</label>
                            <input type="number" wire:model.defer="additionalDays" class="form-control"
                                id="additionalDays">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق
                            </button>
                            <button class="btn btn-primary"> تأكيد
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    UpdatePayment Amount Modal --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="updatePaymentAmountModal" tabindex="-1"
        role="dialog" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">تحديث قيمة الدفع<:< /h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                wire:click="closeModal">
                                <span aria-hidden="true">×</span>
                            </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updatePaymentAmount">
                        <div class="form-group">
                            <label for="updatedPayment">تحديث قيمة الدفع</label>
                            <input type="number" wire:model.defer="updatedPayment" class="form-control"
                                id="updatedPayment">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق
                            </button>
                            <button class="btn btn-primary"> تأكيد
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    <style> --}}
    {{--        .custom-pagination { --}}
    {{--            /* Add your custom styles here */ --}}
    {{--            /* For example: */ --}}
    {{--            display: flex; --}}
    {{--            justify-content: center; --}}
    {{--            margin-top: 20px; --}}
    {{--        } --}}

    {{--        .custom-pagination > nav > ul > li { --}}
    {{--            /* Add styles for individual pagination links */ --}}
    {{--            /* For example: */ --}}
    {{--            margin: 0 5px; --}}
    {{--        } --}}

    {{--        .custom-pagination > nav > ul > li > a { --}}
    {{--            /* Add styles for pagination link anchors */ --}}
    {{--            /* For example: */ --}}
    {{--            padding: 5px 10px; --}}
    {{--            background-color: #ddd; --}}
    {{--            color: #333; --}}
    {{--            text-decoration: none; --}}
    {{--            border-radius: 3px; --}}
    {{--        } --}}

    {{--        /* Add more styles as needed */ --}}

    {{--    </style> --}}



</div>
