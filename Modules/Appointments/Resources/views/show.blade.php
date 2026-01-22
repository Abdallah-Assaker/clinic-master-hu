@extends('layouts.app')

@section('title', 'تفاصيل الحجز')

@section('content')
    <div class="container py-5 mt-5">
        <!-- رسالة التأكيد -->

        @if (session('success'))
        <div class="alert-card success mb-4">
            <div class="alert-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="alert-content">
                <h6 class="alert-heading">تمت العملية بنجاح!</h6>
                <p class="mb-0">{!! session('success') !!}</p>
            </div>
            <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert-card error mb-4">
            <div class="alert-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <h6 class="alert-heading">حدث خطأ!</h6>
                <p class="mb-0">{!! session('error') !!}</p>
            </div>
            <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif

        <div class="row">

            <div class="col-lg-4">
                <!-- بطاقة معلومات الطبيب -->
                <div class="doctor-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="section-title">
                            <i class="bi bi-person-badge"></i>
                            معلومات الطبيب
                        </div>
                        @if($appointment->status === 'completed' )
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#rateModal{{ $appointment->id }}">
                            <i class="bi bi-star-fill me-1"></i>
                            تقييم الدكتور
                        </button>
                        @endif
                    </div>
                    <div class="doctor-profile">
                        <div class="doctor-avatar">
                            @if($appointment->doctor->image)
                                <img src="{{ asset('storage/' . $appointment->doctor->image) }}"
                                    alt="{{ $appointment->doctor->name }}" class="doctor-image"
                                    onerror="this.src='{{ asset('images/default-doctor.png') }}'; this.onerror=null;">
                            @else
                                <div class="avatar-placeholder">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif
                        </div>
                        <h5 class="doctor-name">{{ $appointment->doctor->gender == 'ذكر' ? 'د.' : 'د.' }}
                            {{ $appointment->doctor->name }}
                        </h5>
                        <p class="doctor-title">{{ $appointment->doctor->title }}</p>

                        <div class="doctor-categories">
                            @if($appointment->doctor->category)
                                <span class="category-badge category-badge-0">{{ $appointment->doctor->category->name }}</span>
                            @endif
                        </div>

                        <div class="doctor-rating">
                            <div class="stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($appointment->doctor->rating_avg))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif ($i - 0.5 <= $appointment->doctor->rating_avg)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="rating-text">{{ number_format($appointment->doctor->rating_avg, 1) }}
                                ({{ $appointment->doctor->ratings_count }} تقييم)</div>
                        </div>
                    </div>

                    <div class="doctor-contact">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="contact-text">
                                {{ $appointment->doctor->governorate->name }} - {{ $appointment->doctor->city->name }}
                            </div>
                        </div>

                        @if($appointment->doctor->address)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="contact-text">
                                    {{ $appointment->doctor->address }}
                                </div>
                            </div>
                        @endif

                        @if($appointment->doctor->phone)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div class="contact-text">
                                    {{ $appointment->doctor->phone }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- بطاقة المساعدة والدعم -->
                <div class="support-card">
                    <div class="card-header">
                        <div class="section-title">
                            <i class="bi bi-headset"></i>
                            مساعدة ودعم
                        </div>
                    </div>
                    <div class="support-content">
                        <p>هل تواجه مشكلة أو لديك استفسار بخصوص حجزك؟</p>
                        <div class="support-options">
                            <a href="tel:+201020114717" class="support-option">
                                <i class="bi bi-telephone-fill"></i>
                                <span>+201020114717</span>
                            </a>
                            <a href="mailto:support@clinic.com" class="support-option">
                                <i class="bi bi-envelope-fill"></i>
                                <span>support@clinic.com</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <!-- بطاقة تفاصيل الحجز -->
                <div class="appointment-card mb-4 h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="header-icon {{ $appointment->status }}">
                                    <i class="bi bi-calendar-check-fill"></i>
                                </div>
                                <div>
                                    <h1 class="card-title">تفاصيل الحجز</h1>
                                    <div class="status-badge {{ $appointment->status }}">
                                        <i class="bi bi-circle-fill me-2"></i>
                                        {{ $appointment->status_text }}
                                    </div>
                                </div>
                            </div>

                            <!-- أزرار الإجراءات في الهيدر -->
                            @if($appointment->status === 'scheduled')
                                <div class="header-actions">
                                    @if(!$appointment->is_paid && (!$appointment->payment || $appointment->payment->payment_method !== 'cash'))
                                        <a href="{{ route('payments.checkout', $appointment) }}" class="btn btn-primary me-2">
                                            <i class="bi bi-credit-card-2-front me-2"></i>
                                            دفع الآن
                                        </a>
                                    @endif

                                    <button type="button" class="btn btn-danger" id="cancelbtn">
                                        <i class="bi bi-x-circle me-2"></i>
                                        إلغاء الحجز
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- معلومات الحجز الرئيسية -->
                    <div class="appointment-info-section">
                        <div class="section-title">
                            <i class="bi bi-info-circle-fill"></i>
                            معلومات الحجز
                        </div>

                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon id">
                                    <i class="bi bi-upc-scan"></i>
                                </div>
                                <div class="stat-details">
                                    <div class="stat-label">رقم الحجز</div>
                                    <div class="stat-value">#{{ $appointment->id,}}</div>
                                    {{-- <div class="stat-subtext">{{ $appointment->created_at->locale('ar')->diffForHumans() }}
                                    </div> --}}
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon date">
                                    <i class="bi bi-calendar2-event"></i>
                                </div>
                                <div class="stat-details">
                                    <div class="stat-label">تاريخ الحجز</div>
                                    <div class="stat-value">
                                        {{ $appointment->scheduled_at->locale('ar')->translatedFormat('l') }}
                                    </div>
                                    <div class="stat-subtext">
                                        {{ $appointment->scheduled_at->locale('ar')->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon time">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="stat-details">
                                    <div class="stat-label">وقت الكشف</div>
                                    <div class="stat-value">{{ $appointment->scheduled_at->format('h:i A') }}</div>
                                </div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon time">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div class="stat-details">
                                    <div class="stat-label">وقت الانتظار</div>
                                    <div class="stat-value">{{ $appointment->doctor->waiting_time }} دقيقة</div>
                                </div>
                            </div>


                            <div class="stat-card">
                                <div class="stat-icon price">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="stat-details">
                                    <div class="stat-label">تكلفة الكشف</div>
                                    <div class="stat-value">{{ $appointment->fees }} <span class="currency">ج.م</span></div>

                                    <!-- حالة الدفع -->
                                    <div class="stat-badge {{ $appointment->is_paid ? 'paid' : 'unpaid' }}">
                                        <i class="bi {{ $appointment->is_paid ? 'bi-check-circle-fill' : 'bi-clock-history' }}"></i>
                                        {{ $appointment->is_paid ? 'مدفوع' : 'غير مدفوع' }}
                                    </div>
                                </div>
                            </div>

                          <!-- إضافة بطاقة إحصائية منفصلة لطريقة الدفع -->
                          @if($appointment->payment && $appointment->payment->payment_method)
                          <div class="stat-card">
                              <div class="stat-icon payment">
                                  @if($appointment->payment->payment_method == 'stripe')
                                      <i class="bi bi-credit-card-2-front"></i>
                                  @elseif($appointment->payment->payment_method == 'cash')
                                      <i class="bi bi-cash"></i>
                                  @else
                                      <i class="bi bi-wallet2"></i>
                                  @endif
                              </div>
                              <div class="stat-details">
                                  <div class="stat-label">طريقة الدفع</div>
                                  <div class="stat-value">
                                      @if($appointment->payment->payment_method == 'stripe')
                                          الدفع ببطاقة الائتمان
                                      @elseif($appointment->payment->payment_method == 'cash')
                                          الدفع نقدًا عند الوصول

                                      @endif
                                  </div>

                              </div>
                          </div>
                          @endif




                        </div>
                    </div>

                    @if($appointment->notes)
                        <!-- ملاحظات الحجز -->
                        <div class="appointment-notes-section">
                            <div class="section-title">
                                <i class="bi bi-journal-text"></i>
                                ملاحظات الحجز
                            </div>
                            <div class="notes-content">
                                {{ $appointment->notes }}
                            </div>
                        </div>
                    @endif

                    <!-- تعليمات هامة محسنة مع تصنيف التعليمات وإضافة معلومات أكثر فائدة -->
                    <div class="instructions-section">
                        <div class="section-title">
                            <i class="bi bi-exclamation-triangle"></i>
                            تعليمات هامة
                        </div>
                        <div class="instructions-content">
                            <div class="instructions-category mb-3">
                                <div class="category-header">
                                    <i class="bi bi-clock-history text-primary me-2"></i>
                                    <span class="category-title">قبل الزيارة</span>
                                </div>
                                <ul class="instructions-list">
                                    <li>
                                        <span class="instruction-icon"><i class="bi bi-arrow-up-circle"></i></span>
                                        <span>يرجى الوصول قبل الموعد بـ 15-20 دقيقة لإتمام إجراءات التسجيل</span>
                                    </li>
                                    <li>
                                        <span class="instruction-icon"><i class="bi bi-file-earmark-medical"></i></span>
                                        <span>يجب إحضار البطاقة الشخصية وأي تقارير طبية سابقة إن وجدت</span>
                                    </li>
                                    @if($appointment->payment && $appointment->payment->payment_method == 'cash')
                                    <li>
                                        <span class="instruction-icon"><i class="bi bi-cash"></i></span>
                                        <span>يرجى إحضار المبلغ المطلوب نقداً ({{ $appointment->fees }} ج.م)</span>
                                    </li>
                                    @endif
                                </ul>
                            </div>

                            <div class="instructions-category mb-3">
                                <div class="category-header">
                                    <i class="bi bi-exclamation-circle text-warning me-2"></i>
                                    <span class="category-title">سياسات الإلغاء والتأجيل</span>
                                </div>
                                <ul class="instructions-list">
                                    <li>
                                        <span class="instruction-icon"><i class="bi bi-x-circle"></i></span>
                                        <span>في حالة الرغبة في إلغاء الحجز، يرجى الإلغاء قبل 24 ساعة على الأقل</span>
                                    </li>
                                    <li>
                                        <span class="instruction-icon"><i class="bi bi-arrow-repeat"></i></span>
                                        <span>إذا أردت تأجيل الموعد، يرجى التواصل قبل الموعد بـ 12 ساعة على الأقل</span>
                                    </li>
                                    <li>
                                        <span class="instruction-icon"><i class="bi bi-currency-exchange"></i></span>
                                        <span>في حالة الإلغاء قبل 24 ساعة، يمكنك استرداد قيمة الحجز، أما بعد ذلك فيتم خصم 50% من القيمة</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="instructions-category">
                                <div class="category-header">
                                    <i class="bi bi-headset text-success me-2"></i>
                                    <span class="category-title">الدعم والمساعدة</span>
                                </div>
                                <ul class="instructions-list">
                                    <li>
                                        <span class="instruction-icon"><i class="bi bi-telephone"></i></span>
                                        <span>في حالة وجود استفسارات يمكنك التواصل عبر رقم الدعم: +201020114717</span>
                                    </li>
                                    <li>
                                        <span class="instruction-icon"><i class="bi bi-envelope"></i></span>
                                        <span>للاستفسارات عبر البريد الإلكتروني: support@clinic.com</span>
                                    </li>
                                    <li>
                                        <span class="instruction-icon"><i class="bi bi-map"></i></span>
                                        <span>يمكنك الاطلاع على الخريطة والاتجاهات من خلال الضغط على زر الخريطة في ملف الطبيب</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



<!-- Modal تقييم الدكتور -->
<div class="modal fade" id="rateModal{{ $appointment->id }}"
    tabindex="-1" aria-labelledby="rateModalLabel{{ $appointment->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="rateModalLabel{{ $appointment->id }}">
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    @if($existingRating ?? false)
                        عرض التقييم
                    @else
                        تقييم الدكتور
                    @endif
                </h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form
                action="{{ route('doctors.rate', $appointment->doctor->id) }}"
                method="POST" @if($existingRating ?? false) class="rated-form"
                @endif>
                @csrf
                <input type="hidden" name="appointment_id"
                    value="{{ $appointment->id }}">
                <div class="modal-body">
                    <!-- Doctor info -->
                    <div class="doctor-profile mb-3">
                        <div
                            class="doctor-info d-flex align-items-center">
                            <div class="doctor-avatar">
                                @if($appointment->doctor->image)
                                    <img src="{{ Storage::url($appointment->doctor->image) }}"
                                        alt="{{ $appointment->doctor->name }}"
                                        onerror="this.src='{{ asset('images/default-doctor.png') }}'; this.onerror=null;">
                                @else
                                    <i class="bi bi-person-badge-fill"></i>
                                @endif
                            </div>
                            <div class="doctor-details ms-3">
                                <h5 class="doctor-name mb-1">د.
                                    {{ $appointment->doctor->name }}
                                </h5>
                                <div class="specialization-badge">
                                    {{ $appointment->doctor->category->name ?? 'غير محدد' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($existingRating ?? false)
                        <!-- Mostrar valoración existente -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            لقد قمت بتقييم الدكتور مسبقًا
                        </div>
                    @endif

                    <!-- Star rating -->
                    <div class="rating-container">
                        <h6 class="text-center mb-2">
                            @if($existingRating ?? false)
                                تقييمك للدكتور
                            @else
                                كيف كانت تجربتك مع الدكتور؟
                            @endif
                        </h6>
                        <div class="star-rating">
                            <div class="stars @if($existingRating ?? false) readonly @endif">
                                <input type="radio"
                                    id="star5-{{ $appointment->id }}"
                                    name="rating" value="5"
                                    class="visually-hidden"
                                    @if(($existingRating ?? false) && $existingRating->rating == 5) checked
                                    @endif @if($existingRating ?? false) disabled
                                    @endif>
                                <label
                                    for="star5-{{ $appointment->id }}"
                                    class="star-label @if(($existingRating ?? false) && $existingRating->rating >= 5) selected @endif"
                                    title="ممتاز - 5 نجوم">★</label>

                                <input type="radio"
                                    id="star4-{{ $appointment->id }}"
                                    name="rating" value="4"
                                    class="visually-hidden"
                                    @if(($existingRating ?? false) && $existingRating->rating == 4) checked
                                    @endif @if($existingRating ?? false) disabled
                                    @endif>
                                <label
                                    for="star4-{{ $appointment->id }}"
                                    class="star-label @if(($existingRating ?? false) && $existingRating->rating >= 4) selected @endif"
                                    title="جيد جدا - 4 نجوم">★</label>

                                <input type="radio"
                                    id="star3-{{ $appointment->id }}"
                                    name="rating" value="3"
                                    class="visually-hidden"
                                    @if(($existingRating ?? false) && $existingRating->rating == 3) checked
                                    @endif @if($existingRating ?? false) disabled
                                    @endif>
                                <label
                                    for="star3-{{ $appointment->id }}"
                                    class="star-label @if(($existingRating ?? false) && $existingRating->rating >= 3) selected @endif"
                                    title="جيد - 3 نجوم">★</label>

                                <input type="radio"
                                    id="star2-{{ $appointment->id }}"
                                    name="rating" value="2"
                                    class="visually-hidden"
                                    @if(($existingRating ?? false) && $existingRating->rating == 2) checked
                                    @endif @if($existingRating ?? false) disabled
                                    @endif>
                                <label
                                    for="star2-{{ $appointment->id }}"
                                    class="star-label @if(($existingRating ?? false) && $existingRating->rating >= 2) selected @endif"
                                    title="مقبول - 2 نجوم">★</label>

                                <input type="radio"
                                    id="star1-{{ $appointment->id }}"
                                    name="rating" value="1"
                                    class="visually-hidden"
                                    @if(($existingRating ?? false) && $existingRating->rating == 1) checked
                                    @endif @if($existingRating ?? false) disabled
                                    @endif>
                                <label
                                    for="star1-{{ $appointment->id }}"
                                    class="star-label @if(($existingRating ?? false) && $existingRating->rating >= 1) selected @endif"
                                    title="ضعيف - نجمة واحدة">★</label>
                            </div>
                            <div class="rating-value text-center mt-2">
                                @if($existingRating ?? false)
                                    {{ $existingRating->rating }} من 5
                                @else
                                    <span class="text-muted">اختر تقييمك</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div class="mb-3 mt-3">
                        <label for="comment-{{ $appointment->id }}"
                            class="form-label">تعليقك (اختياري)</label>
                        <textarea class="form-control"
                            id="comment-{{ $appointment->id }}"
                            name="comment" rows="3" @if($existingRating ?? false)
                            readonly @endif
                            placeholder="اكتب تعليقك هنا...">{{ ($existingRating ?? false) ? $existingRating->comment : '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        @if($existingRating ?? false)
                            إغلاق
                        @else
                            إلغاء
                        @endif
                    </button>
                    @if(!($existingRating ?? false))
                        <button type="submit" class="btn btn-primary">إرسال
                            التقييم</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>


    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // إضافة معالج الحدث لزر الإلغاء
                const cancelbtn = document.getElementById('cancelbtn');

                if (cancelbtn) {
                    cancelbtn.addEventListener('click', function (e) {
                        // إظهار النافذة المنبثقة لتأكيد الإلغاء
                        showCancellationPopup();
                    });
                }

                // إغلاق النافذة المنبثقة عند النقر خارجها
                document.addEventListener('click', function (e) {
                    const popup = document.getElementById('cancellationPopup');
                    const popupContent = document.querySelector('.popup-content');

                    if (popup && e.target === popup && !popupContent.contains(e.target)) {
                        popup.classList.remove('show');
                    }
                });
            });

            // دالة لإظهار النافذة المنبثقة
            function showCancellationPopup() {
                // إنشاء النافذة المنبثقة إذا لم تكن موجودة بالفعل
                let popup = document.getElementById('cancellationPopup');

                if (!popup) {
                    popup = document.createElement('div');
                    popup.id = 'cancellationPopup';
                    popup.className = 'popup-overlay';

                    const popupHTML = `
                            <div class="popup-content">
                                <div class="popup-header">
                                    <div class="popup-icon warning">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                    <h3 class="popup-title">تأكيد إلغاء الحجز</h3>
                                </div>
                                <div class="popup-body">
                                    <p>هل أنت متأكد من إلغاء هذا الحجز؟</p>
                                    <p class="text-danger small">لا يمكن التراجع عن هذه العملية بعد تأكيدها.</p>
                                </div>
                                <div class="popup-actions">
                                    <button type="button" class="btn btn-outline-secondary" id="cancelButton">
                                        تراجع
                                    </button>

                                    <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" id="cancelForm">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger" id="confirmButton">
                                            <i class="bi bi-x-circle me-2"></i>
                                            تأكيد الإلغاء
                                        </button>
                                    </form>
                                </div>
                            </div>
                        `;

                    popup.innerHTML = popupHTML;
                    document.body.appendChild(popup);
                }

                // إضافة أحداث للأزرار - نضيفها في كل مرة لضمان عملها
                const cancelButton = document.getElementById('cancelButton');
                const confirmButton = document.getElementById('confirmButton');
                const cancelForm = document.getElementById('cancelForm');

                // إزالة المستمعين القديمة لمنع التكرار
                cancelButton.replaceWith(cancelButton.cloneNode(true));
                confirmButton.replaceWith(confirmButton.cloneNode(true));

                // إعادة تعريف المتغيرات بعد الإعادة
                const newCancelButton = document.getElementById('cancelButton');
                const newConfirmButton = document.getElementById('confirmButton');

                // إضافة المستمعين الجدد
                newCancelButton.addEventListener('click', function () {
                    popup.classList.remove('show');
                });

                // ضمان عدم منع السلوك الافتراضي للنموذج
                cancelForm.addEventListener('submit', function(e) {
                    // السماح بتقديم النموذج بشكل طبيعي
                });

                // إظهار النافذة المنبثقة
                popup.classList.add('show');
            }
        </script>
    @endpush

    @push('styles')
        <style>
            /* ======= POPUP STYLES ======= */
            .popup-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                backdrop-filter: blur(4px);
            }

            .popup-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            .popup-content {
                background-color: white;
                border-radius: 15px;
                width: 100%;
                max-width: 450px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                transform: translateY(20px);
                transition: transform 0.3s ease;
                overflow: hidden;
            }

            .popup-overlay.show .popup-content {
                transform: translateY(0);
            }

            .popup-header {
                padding: 1.5rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .popup-icon {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .popup-icon.warning {
                background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.15) 100%);
                color: var(--danger-color);
                font-size: 1.5rem;
            }

            .popup-title {
                font-size: 1.25rem;
                font-weight: 600;
                margin: 0;
                color: #2d3748;
            }

            .popup-body {
                padding: 1.5rem;
                color: #4a5568;
            }

            .popup-body p {
                margin-bottom: 0.75rem;
            }

            .popup-body p:last-child {
                margin-bottom: 0;
            }

            .popup-actions {
                padding: 1rem 1.5rem;
                display: flex;
                justify-content: flex-end;
                gap: 0.75rem;
                background-color: #f8fafc;
                border-top: 1px solid rgba(0, 0, 0, 0.05);
            }

            @media (max-width: 576px) {
                .popup-content {
                    max-width: calc(100% - 2rem);
                    margin: 0 1rem;
                }

                .popup-actions {
                    flex-direction: column-reverse;
                }

                .popup-actions button {
                    width: 100%;
                }
            }


            /* ======= APPOINTMENT CARD STYLES ======= */
            .appointment-card,
            .doctor-card,
            .support-card {
                background: #ffffff;
                border-radius: var(--border-radius);
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            }

            .card-header {
                padding: 1rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                position: relative;
            }

            .header-icon {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.75rem;
                flex-shrink: 0;
            }

            .header-icon.scheduled {
                background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(147, 51, 234, 0.15) 100%);
                color: #9333ea;
            }

            .header-icon.completed {
                background-color: var(--success-light);
                color: var(--success-color);
            }

            .header-icon.cancelled {
                background-color: var(--danger-light);
                color: var(--danger-color);
            }

            .card-title {
                margin: 0;
                font-size: 1.5rem;
                font-weight: 600;
                color: #2d3748;
            }



            /* ======= HEADER ACTIONS ======= */
            .header-actions {
                display: flex;
                align-items: center;
            }

            .header-actions .btn-danger {
                background-color: var(--danger-color);
                border-color: var(--danger-color);
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                transition: all 0.3s ease;
                white-space: nowrap;
            }

            .header-actions .btn-danger:hover {
                background-color: #d32f2f;
                box-shadow: 0 4px 8px rgba(227, 52, 47, 0.25);
                transform: translateY(-2px);
            }

            /* ======= SECTIONS STYLES ======= */
            .appointment-info-section,
            .appointment-notes-section,
            .instructions-section,
            .actions-section {
                padding: 1.5rem;
            }

            .actions-section {
                padding: 1.5rem;
                border-bottom: none;
                text-align: center;
            }

            .section-title {
                display: flex;
                align-items: center;
                font-weight: 600;
                color: #2d3748;
            }

            .section-title i {
                margin-left: 0.75rem;
                font-size: 1.25rem;
                color: var(--primary-color);
            }

            /* ======= STATS GRID STYLES ======= */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .stat-card {
                display: flex;
                align-items: flex-start;
                border-radius: 12px;
                padding: 1rem;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }


            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                margin-left: 1rem;
                flex-shrink: 0;
            }

            .stat-icon.date {
                background-color: var(--primary-light);
                color: var(--primary-color);
            }

            .stat-icon.time {
                background-color: var(--info-light);
                color: var(--info-color);
            }

            .stat-icon.price {
                background-color: var(--success-light);
                color: var(--success-color);
            }

            .stat-icon.payment {
                background-color: var(--purple-light, #f3e8ff);
                color: var(--purple-color, #9333ea);
            }

            .stat-icon.id {
                background-color: var(--warning-light);
                color: var(--warning-color);
            }

            .stat-icon.duration {
                background-color: var(--info-light);
                color: var (--info-color);
            }

            .stat-details {
                flex: 1;
            }

            .stat-label {
                font-size: 0.875rem;
                color: #64748b;
                margin-bottom: 0.25rem;
            }

            .stat-value {
                font-size: 1.25rem;
                font-weight: 600;
                color: #2d3748;
                margin-bottom: 0.25rem;
                line-height: 1.2;
            }

            .stat-value .currency {
                font-size: 0.875rem;
                font-weight: normal;
                margin-right: 0.25rem;
            }

            .stat-subtext {
                font-size: 0.875rem;
                color: #64748b;
            }

            .payment-info {
                display: flex;
                align-items: center;
                margin-top: 0.5rem;
                gap: 0.5rem;
            }

            .payment-card-icon {
                width: 30px;
                height: 20px;
                border-radius: 4px;
                background-color: #f8f9fa;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
                color: #6c757d;
                border: 1px solid rgba(0,0,0,0.1);
            }

            .payment-info-text {
                font-size: 0.75rem;
                color: #64748b;
                font-style: italic;
            }

            .stat-badge {
                display: inline-flex;
                align-items: center;
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
                border-radius: 50px;
            }

            .stat-badge.paid {
                background-color: var(--success-light);
                color: var(--success-color);
            }

            .stat-badge.unpaid {
                background-color: var(--warning-light);
                color: var(--warning-color);
            }

            .stat-badge i {
                margin-left: 0.25rem;
            }

            .duration-details {
                margin-top: 0.5rem;
            }

            .duration-item {
                display: flex;
                align-items: center;
                font-size: 0.875rem;
                color: #4a5568;
                margin-bottom: 0.25rem;
            }

            .duration-item i {
                margin-right: 0.5rem;
            }

            /* ======= NOTES SECTION STYLES ======= */
            .notes-content {
                background-color: #f8fafc;
                border-radius: 12px;
                padding: 1rem;
                color: #4a5568;
                line-height: 1.6;
            }

            /* ======= INSTRUCTIONS SECTION STYLES ======= */
            .instructions-content {
                border-radius: 12px;
                padding: 1rem;
            }

            .instructions-list {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .instructions-list li {
                display: flex;
                margin-bottom: 0.875rem;
                color: #4a5568;
            }

            .instructions-list li:last-child {
                margin-bottom: 0;
            }

            .instruction-icon {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background-color: var(--primary-light);
                color: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
                margin-left: 0.75rem;
                flex-shrink: 0;
            }

            /* ======= DOCTOR CARD STYLES ======= */
            .doctor-profile {
                padding: 1.5rem;
                text-align: center;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .doctor-avatar {
                width: 120px;
                height: 120px;
                margin: 0 auto 1rem;
                position: relative;
                border-radius: 50%;
                overflow: hidden;
                border: 4px solid #fff;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            }

            .doctor-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .avatar-placeholder {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(45deg, #e9ecef 0%, #f8f9fa 100%);
                color: #6c757d;
                font-size: 3rem;
            }

            .doctor-name {
                margin-bottom: 0.25rem;
                font-weight: 600;
                color: #2d3748;
                font-size: 1.25rem;
            }

            .doctor-title {
                color: var(--primary-color);
                margin-bottom: 1rem;
                font-size: 0.95rem;
            }

            .doctor-categories {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                justify-content: center;
                margin-bottom: 1rem;
            }

            .category-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 50px;
                font-size: 0.75rem;
                background-color: #e9ecef;
                color: #495057;
            }

            .category-badge-0 {
                background-color: #e3f2fd;
                color: #0d6efd;
            }

            .category-badge-1 {
                background-color: #e8f5e9;
                color: #28a745;
            }

            .category-badge-2 {
                background-color: #fff3cd;
                color: #ffc107;
            }

            .category-badge-3 {
                background-color: #f8d7da;
                color: #dc3545;
            }

            .category-badge-4 {
                background-color: #d1ecf1;
                color: #17a2b8;
            }

            .category-badge-5 {
                background-color: #f5e6ff;
                color: #6610f2;
            }

            .doctor-rating {
                margin-bottom: 0.5rem;
            }

            .stars {
                color: #ffc107;
                font-size: 1.1rem;
                margin-bottom: 0.25rem;
            }

            .rating-text {
                color: #6c757d;
                font-size: 0.875rem;
            }

            .doctor-contact {
                padding: 1.5rem;
            }

            .contact-item {
                display: flex;
                margin-bottom: 0.75rem;
            }

            .contact-item:last-child {
                margin-bottom: 0;
            }

            .contact-icon {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                background-color: var(--primary-light);
                color: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                margin-left: 1rem;
                flex-shrink: 0;
            }

            .contact-text {
                flex: 1;
                font-size: 0.95rem;
                color: #4a5568;
                display: flex;
                align-items: center;
            }

            /* ======= SUPPORT CARD STYLES ======= */
            .support-content {
                padding: 1.5rem;
            }

            .support-content p {
                margin-bottom: 1rem;
                color: #4a5568;
            }

            .support-options {
                display: grid;
                grid-template-columns: 1fr;
            }

            .support-option {
                display: flex;
                align-items: center;
                padding: 8px;
                border-radius: 8px;
                color: #4a5568;
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .support-option:hover {
                background-color: var(--primary-light);
                color: var(--primary-color);
                transform: translateY(-2px);
            }

            .support-option i {
                margin-left: 0.75rem;
                font-size: 1.1rem;
                color: var(--primary-color);
            }


            /* ======= ANIMATIONS ======= */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* ======= RESPONSIVE STYLES ======= */
            @media (max-width: 991.98px) {
                .stats-grid {
                    grid-template-columns: 1fr;
                }

                .header-icon {
                    width: 56px;
                    height: 56px;
                    font-size: 1.5rem;
                }

                .card-title {
                    font-size: 1.35rem;
                }

                .doctor-avatar {
                    width: 100px;
                    height: 100px;
                }

                .card-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 1rem;
                }

                .header-actions {
                    align-self: flex-end;
                    margin-top: -2rem;
                }
            }

            @media (max-width: 575.98px) {
                .alert-card {
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                    border-right: none;
                    border-top: 5px solid var(--success-color);
                }

                .alert-icon {
                    margin: 0 0 1rem 0;
                }

                .appointment-info-section,
                .appointment-notes-section,
                .instructions-section,
                .actions-section {
                    padding: 1.25rem;
                }

                .doctor-contact {
                    padding: 1.25rem;
                }

                .support-content {
                    padding: 1.25rem;
                }

                .card-header .d-flex {
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                    width: 100%;
                }

                .header-actions {
                    margin-top: 1rem;
                    width: 100%;
                }

                .header-actions form {
                    width: 100%;
                }

                .header-actions .btn-danger {
                    width: 100%;
                }
            }

            /* Payment Section Styles */
            .payment-section {
                padding: 1.5rem;
                border-top: 1px solid rgba(0, 0, 0, 0.05);
            }

            .payment-options {
                background-color: #f8fafc;
                border-radius: 12px;
                border: 1px solid rgba(0, 0, 0, 0.05);
            }



            .header-actions .btn-primary:hover {
                box-shadow: 0 4px 8px rgba(90, 134, 237, 0.25);
                transform: translateY(-2px);
            }

            @media (max-width: 575.98px) {
                .header-actions {
                    flex-direction: column;
                    gap: 0.5rem;
                    width: 100%;
                }

                .header-actions .btn {
                    width: 100%;
                }
            }

            /* Star Rating Styles */
            .star-rating {
                text-align: center;
                margin-bottom: 1rem;
            }

            .stars {
                display: inline-flex;
                flex-direction: row-reverse;
                justify-content: center;
                font-size: 2rem;
            }

            .star-label {
                cursor: pointer;
                color: #ddd;
                padding: 0 0.2rem;
                transition: all 0.2s ease;
            }

            .star-label:hover,
            .star-label:hover ~ .star-label,
            input[type="radio"]:checked ~ .star-label {
                color: #ffb700;
            }

            .star-label.selected {
                color: #ffb700;
            }

            .rating-value {
                font-weight: bold;
                color: #4a5568;
            }

            /* Doctor Avatar in Modal */
            .modal .doctor-avatar {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                overflow: hidden;
                flex-shrink: 0;
                background-color: #f8f9fa;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .modal .doctor-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .modal .doctor-avatar i {
                font-size: 1.5rem;
                color: #6c757d;
            }

            .modal .doctor-details {
                flex: 1;
            }

            .specialization-badge {
                background-color: rgba(13, 110, 253, 0.1);
                color: var(--primary-color);
                border-radius: 50px;
                padding: 0.25rem 0.75rem;
                font-size: 0.75rem;
                display: inline-block;
            }

            /* تنسيق النجوم في وضع القراءة فقط */
            .stars.readonly .star-label {
                cursor: default;
                pointer-events: none;
            }

            .stars.readonly .star-label:hover,
            .stars.readonly .star-label:hover ~ .star-label {
                color: inherit; /* لا تغير لون النجوم عند التحويم في حالة القراءة فقط */
            }
        </style>
    @endpush



 @endsection
