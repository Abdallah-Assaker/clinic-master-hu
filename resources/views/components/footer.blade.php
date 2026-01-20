<footer class="modern-footer">
    <div class="footer-content">
        <!-- الجزء الرئيسي -->
        <div class="footer-main">
            <div class="container">
                <div class="row justify-content-center">
                    <!-- معلومات العيادة -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="clinic-info">
                            <div class="logo-section mb-3">
                                <i class="fas fa-heart text-primary me-2"></i>
                                <h4 class="clinic-name">Clinic Master</h4>
                            </div>
                            <p class="clinic-description">
                                منصة شاملة تربط بين المرضى والأطباء لتوفير أفضل رعاية صحية. نهدف
                                إلى تسهيل الوصول للخدمات الطبية المتميزة وجعل الصحة في متناول
                                الجميع.
                            </p>
                            <div class="rating-section">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="rating-text">معتمد وآمن</span>
                                <div class="stars">
                                    <i class="fas fa-star text-warning"></i>
                                    <span class="rating-value">4.8/5 تقييم</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- معلومات التواصل -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <h5 class="footer-title">تواصل معنا</h5>
                        <div class="contact-info">
                            <div class="contact-item">
                                <div class="contact-icon email">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-details">
                                    <span class="contact-label">البريد الإلكتروني</span>
                                    <span class="contact-value">info@clinicmaster.com</span>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon phone">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-details">
                                    <span class="contact-label">الهاتف</span>
                                    <span class="contact-value">01500555949</span>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon location">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-details">
                                    <span class="contact-label">العنوان</span>
                                    <span class="contact-value">القاهرة، مصر</span>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon clock">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="contact-details">
                                    <span class="contact-label">ساعات العمل</span>
                                    <span class="contact-value">خدمة متاحة 24/7</span>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- معلومات الفريق -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <h5 class="footer-title">فريق المشروع</h5>
                        <div class="team-info">
                            <div class="team-item">
                                <div class="team-icon submitted">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="team-details">
                                    <span class="team-label">Submitted by</span>
                                    <div class="team-members">
                                        <span class="team-member">1. Abdallah Assaker</span>
                                        <span class="team-member">2. Alaa Abd El Keriem</span>
                                        <span class="team-member">3. Ahmed Tarek</span>
                                        <span class="team-member">4. Mohamed Kamal</span>
                                        <span class="team-member">5. Khaled Waleed</span>
                                    </div>
                                </div>
                            </div>

                            <div class="team-item">
                                <div class="team-icon supervised">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="team-details">
                                    <span class="team-label">Supervised by</span>
                                    <span class="team-value supervisor">Dr. Ahmed Hesham</span>
                                </div>
                            </div>

                            <div class="team-item">
                                <div class="team-icon location-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="team-details">
                                    <span class="team-label">المكان والسنة</span>
                                    <span class="team-value">Cairo, Egypt - 2025</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الجزء السفلي -->
        <div class="footer-bottom">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="copyright">© {{ date('Y') }} Clinic Master. جميع الحقوق محفوظة.</p>
                    <div class="footer-badge">
                        <i class="fas fa-heart text-danger"></i>
                        <span>مطور بواسطة تيم كلينك ماستر    </span>
                        <span class=mx-2>|</span>
                        <span>من أجل صحة أفضل</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .modern-footer {
        background: linear-gradient(135deg, #1a1d29 0%, #2d3748 100%);
        color: #e2e8f0;
        margin-top: auto;
    }

    .footer-content {
        position: relative;
    }

    .footer-main {
        padding: 4rem 0 2rem;
        position: relative;
    }

    .footer-main::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, #4a5568, transparent);
    }

    /* معلومات العيادة */
    .clinic-info .logo-section {
        display: flex;
        align-items: center;
    }

    .clinic-name {
        color: #fff;
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0;
        font-family: "Cairo", "Tajawal", sans-serif;
    }

    .clinic-description {
        color: #a0aec0;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .rating-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .rating-text {
        color: #68d391;
        font-weight: 500;
    }

    .stars {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .rating-value {
        color: #fbb6ce;
        font-size: 0.9rem;
    }

    /* الأيقونات الاجتماعية */
    .social-media {
        display: flex;
        gap: 1rem;
    }

    .social-link {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .social-link.facebook {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .social-link.twitter {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .social-link.pinterest {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }

    .social-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    /* عنوان الأقسام */
    .footer-title {
        color: #fff;
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        font-family: "Cairo", "Tajawal", sans-serif;
    }

    /* معلومات التواصل */
    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .contact-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .contact-icon.email {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
    }

    .contact-icon.phone {
        background: linear-gradient(135deg, #10b981, #047857);
        color: white;
    }

    .contact-icon.location {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: white;
    }

    .contact-icon.clock {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .contact-details {
        display: flex;
        flex-direction: column;
    }

    .contact-label {
        color: #a0aec0;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }

    .contact-value {
        color: #e2e8f0;
        font-weight: 500;
        font-size: 0.95rem;
    }

    /* معلومات الفريق */
    .team-info {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .team-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .team-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .team-icon.submitted {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
    }

    .team-icon.supervised {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .team-icon.location-icon {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: white;
    }

    .team-details {
        display: flex;
        flex-direction: column;
    }

    .team-label {
        color: #a0aec0;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }

    .team-value {
        color: #e2e8f0;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .team-value.supervisor {
        color: #fbbf24;
        font-weight: 600;
    }

    .team-members {
        display: flex;
        flex-direction: column;
    }

    .team-member {
        color: #e2e8f0;
        font-size: 0.85rem;
        line-height: 1.4;
        margin-bottom: 0.2rem;
    }

    /* الجزء السفلي */
    .footer-bottom {
        background: rgba(0,0,0,0.2);
        padding: 1.5rem 0;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .copyright {
        color: #a0aec0;
        margin: 0;
        font-size: 0.9rem;
    }

    .footer-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #a0aec0;
        font-size: 0.9rem;
    }

    .footer-badge .fas.fa-heart {
        font-size: 1rem;
        animation: heartbeat 2s ease-in-out infinite;
    }

    @keyframes heartbeat {
        0%, 50%, 100% { transform: scale(1); }
        25%, 75% { transform: scale(1.1); }
    }

    /* الاستجابة */
    @media (max-width: 768px) {
        .footer-main {
            padding: 3rem 0 1.5rem;
        }

        .clinic-info {
            text-align: center;
            margin-bottom: 2rem;
        }

        .social-media {
            justify-content: center;
        }

        .footer-bottom .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .contact-item {
            justify-content: center;
            text-align: center;
        }

        .team-item {
            justify-content: center;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .clinic-name {
            font-size: 1.3rem;
        }

        .footer-title {
            font-size: 1.1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
        }
    }
</style>
