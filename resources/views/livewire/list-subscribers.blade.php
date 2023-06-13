{{--data-toggle="modal" data-target="#addSubscriberModal"--}}
<x-slot name="action">
    <div class="widgetbar">
        <button data-toggle="modal" data-target="#addSubscriberModal" class="btn btn-primary-rgba"><i
                class="feather icon-plus mr-2"></i>اضافة مشترك
        </button>
    </div>
</x-slot>
<x-slot:pageHeader>
    Subscribers
</x-slot:pageHeader>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <h5 class="card-title">المشتركون</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="default-datatable" class="display table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">الرقم</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">الجوال</th>
                        <th scope="col">الصورة</th>
                        <th scope="col">تاريخ التسجيل</th>
                        <th scope="col">تاريخ الانتهاء</th>
                        <th scope="col">المدة</th>
                        <th scope="col">حالة الاشتراك</th>
                        <th scope="col">الأوامر</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($subscribers as $key => $subscriber)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $subscriber->name }}</td>
                            <td>{{ $subscriber->phone }}</td>
                            <td><img src="{{ asset('storage/subscribers/' .$subscriber->image) }}" width="60px"
                                     height="60px">
                            </td>
                            <td>
                                @if ($subscriber->subscription)
                                    {{ \Carbon\Carbon::parse($subscriber->subscription->start_date)->format('Y-m-d')??'' }}
                                @else

                                @endif

                            </td>
                            <td>
                                @if ($subscriber->subscription)
                                    {{ \Carbon\Carbon::parse($subscriber->subscription->end_date)->format('Y-m-d')??'' }}
                                @else
                                @endif
                            </td>
                            <td>
                                @if ($subscriber->subscription)
                                    {{ $subscriber->subscription->duration_in_months ?? '' }} Months
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
                                        <span
                                            class="badge badge-primary">{{ $subscriber->subscription->status }}
                                        </span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">عير مشترك </span>
                                @endif
                            </td>

                            <td class="row">
                                <button wire:click="deleteSubscriberModal({{$subscriber->id}})" type="button"
                                        class="btn btn-round btn-danger-rgba"><i class="feather icon-trash-2"></i>
                                </button>
                                <button wire:click="editSubscriberModal({{$subscriber->id}})" type="button"
                                        class="btn btn-round btn-warning-rgba"><i class="feather icon-upload"></i>
                                </button>
                                @if ($subscriber->subscription)
                                    @if ($subscriber->subscription->status == 'active')
                                        <!-- Hide the "Subscribe" button -->
                                    @elseif ($subscriber->subscription->status == 'expired')
                                        <button wire:click="updateSubscriptionModal({{$subscriber->id}})" type="button"
                                                class="btn btn-rounded btn-primary-rgba">تجديد
                                        </button>
                                    @endif
                                @else
                                    <button wire:click="createSubscriptionModal({{$subscriber->id}})" type="button"
                                            class="btn btn-round btn-success"><i
                                            class="feather icon-plus"></i></button>
                                @endif
                                @if($subscriber->subscription && $subscriber->subscription->status =='active')
                                    <div class="btn-group mr-2">
                                        <div class="dropdown show">
                                            <button class="btn btn-round btn-primary-rgba " type="button"
                                                    id="CustomdropdownMenuButton3" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="true"><i
                                                    class="feather icon-more-vertical-"></i></button>
                                            <div class="dropdown-menu"
                                                 aria-labelledby="CustomdropdownMenuButton3"
                                                 x-placement="bottom-start"
                                                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 40px, 0px);">
                                                <button
                                                    wire:click="deleteSubscriptionModal({{$subscriber->subscription->id ??''}})"
                                                    class="dropdown-item">
                                                    حذف الاشتراك
                                                </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{--    Add Subscriber Modal--}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="addSubscriberModal"
         tabindex="-1" role="dialog"
         aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">اضافة مشترك</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeSubscriber" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input wire:model.defer="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror" id="name"
                                   aria-describedby="emailHelp"
                                   placeholder="Enter name"
                            >
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input wire:model.defer="phone" type="number"
                                   class="form-control @error('name') is-invalid @enderror" id="phone"
                                   placeholder="Phone"
                            >
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input wire:model="image" type="file" class="form-control" id="image"
                            >
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
    {{--    Edit Subscriber Modal--}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="editSubscriberModal"
         tabindex="-1" role="dialog"
         aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel"> تعديل بيانات المشترك </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="editSubscriber" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input wire:model.defer="editSubscriberName" type="text"
                                   class="form-control @error('name') is-invalid @enderror" id="name"
                                   aria-describedby="emailHelp"
                                   placeholder="Enter name"
                            >
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input wire:model.defer="editSubscriberPhone" type="number"
                                   class="form-control @error('name') is-invalid @enderror" id="phone"
                                   placeholder="Phone"
                            >
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input wire:model="image" type="file" class="form-control file" id="image"
                            >
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>@enderror
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
    {{--    Delete Subscriber Modal--}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="deleteSubscriberModal"
         tabindex="-1" role="dialog"
         aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">حذف مشترك</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>هل تريد حذف المشترك؟ </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    >اغلاق
                    </button>
                    <button wire:click="deleteSubscriber" class="btn btn-primary"> حذف المشترك
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{--    Add Subscription Modal--}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="createSubscriptionModal"
         tabindex="-1" role="dialog"
         aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">اضافة اشتراك
                        للمشترك: {{$subscriber->name ??''}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
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
                                <input type="date" wire:model="customEndDate" class="form-control" id="customEndDate">
                            </div>
                        @endif
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                            <button type="submit" class="btn btn-primary">تجديد الاشتراك</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    Renew Subscription Modal--}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="updateSubscriptionModal"
         tabindex="-1" role="dialog"
         aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">
                        تجديد الاشتراك للمشترك: {{$subscriber->name ??''}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
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
                                <input type="date" wire:model="customEndDate" class="form-control" id="customEndDate">
                            </div>
                        @endif
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                            <button type="submit" class="btn btn-primary">تجديد الاشتراك</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    Delete Subscription Modal--}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg show" id="deleteSubscriptionModal"
         tabindex="-1" role="dialog"
         aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleLargeModalLabel">حذف الاشتراك للمشترك:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>هل تريد حذف الاشتراك؟ </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    >اغلاق
                    </button>
                    <button wire:click="deleteSubscription" class="btn btn-primary"> حذف الاشتراك
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

