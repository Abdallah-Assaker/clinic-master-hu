# Clinic Master - Product Manual

**Version:** 1.0  
**Date:** January 2, 2026  
**Target Audience:** Clinic Owners, Administrators, and Stakeholders

---

## 1. System Overview

**Clinic Master** is a comprehensive, web-based medical practice management solution designed to streamline the interaction between clinic administrators, doctors, and patients. It replaces manual appointment booking and record-keeping with a centralized, digital ecosystem.

### Key Value Propositions
*   **Centralized Management:** One dashboard to oversee doctors, patients, and financial operations.
*   **Automated Scheduling:** Eliminates double-booking and manual schedule coordination.
*   **Patient Self-Service:** Empowers patients to find doctors and book appointments 24/7.
*   **Financial Integration:** Built-in payment processing via Stripe.
*   **Localized Experience:** Fully optimized for Arabic-speaking regions (RTL design).

---

## 2. User Roles & Responsibilities

The system is built around three distinct user roles, each with a specialized workspace.

### 🛡️ Administrator (Clinic Manager)
The super-user responsible for the operational setup and maintenance of the clinic.
*   **Capabilities:**
    *   Create and manage Doctor accounts and profiles.
    *   Manage Patient records.
    *   Configure medical specialties and categories.
    *   Oversee all appointments and financial transactions.
    *   View system-wide analytics and charts.
*   **Limitations:** Cannot perform medical consultations directly.

### 👨‍⚕️ Doctor (Medical Provider)
The medical professional who delivers care.
*   **Capabilities:**
    *   Manage personal professional profile (bio, fees, experience).
    *   Set weekly availability and working hours.
    *   View upcoming daily/weekly appointment schedules.
    *   Mark appointments as "Completed" or "Cancelled".
*   **Limitations:** Cannot delete other doctors or access system settings.

### 👤 Patient (Customer)
The end-user seeking medical care.
*   **Capabilities:**
    *   Register and manage a personal profile.
    *   Search for doctors by specialty, location, price, or name.
    *   Book appointments based on real-time availability.
    *   Pay for consultations online.
    *   View appointment history and status.
*   **Limitations:** Restricted to their own data only.

---

## 3. End-to-End Functional Cycles

### 🔄 Admin Cycle: Clinic Setup & Management
1.  **Login:** Admin logs in via the secure portal.
2.  **Dashboard Overview:** Lands on `/dashboard` to view daily stats (Total Doctors, Patients, Appointments).
3.  **Onboarding a Doctor:**
    *   Navigates to **Doctors > Add New**.
    *   Enters credentials (email/password) and professional details (Specialty, Fee, Experience).
    *   **Crucial Step:** Sets the doctor's "Weekly Schedule" (e.g., Sunday-Thursday, 9 AM - 5 PM).
    *   System automatically generates available slots for patients.
4.  **Monitoring:** Admin tracks "Incomplete Profiles" to ensure all doctors have valid data.

### 🔄 Patient Cycle: Booking & Care
1.  **Registration:** Patient signs up at `/register` with name, email, and phone.
2.  **Discovery:**
    *   Uses the **Search** bar to find a "Cardiologist" in "Cairo".
    *   Filters results by "Consultation Fee" or "Waiting Time".
3.  **Booking:**
    *   Selects a doctor and clicks **Book Now**.
    *   Chooses a specific date and time slot from the doctor's real-time availability.
    *   Adds notes (e.g., "First time visit").
4.  **Payment:** Completes payment via the integrated Stripe checkout.
5.  **Confirmation:** Receives an email confirmation and sees the appointment in "My Appointments".

### 🔄 Doctor Cycle: Daily Operations
1.  **Start of Day:** Doctor logs in to `/doctors/profile`.
2.  **Schedule Check:** Clicks **My Appointments** to see the list of patients for the day.
3.  **Consultation:**
    *   Patient arrives.
    *   Doctor performs the consultation.
4.  **Completion:**
    *   Doctor clicks **Complete** on the appointment card.
    *   System updates the status to "Completed" and archives it in the patient's history.

---

## 4. Feature Catalog

### 🔐 Authentication & Security
*   **Role-Based Access Control (RBAC):** Strict separation of data between Admins, Doctors, and Patients.
*   **Secure Login:** Encrypted password management.
*   **Password Reset:** Self-service email-based password recovery.

### 📅 Scheduling & Appointments
*   **Dynamic Slot Generation:** Slots are created automatically based on doctor's working hours.
*   **Conflict Prevention:** System prevents double-booking of the same slot.
*   **Status Tracking:** Appointments move through states: `Pending` -> `Confirmed` -> `Completed` / `Cancelled`.

### 🏥 Medical Management
*   **Specialty Management:** Admin can define and categorize medical specialties.
*   **Doctor Profiles:** Rich profiles including photo, bio, degree, and waiting time.
*   **Geographic Filtering:** Filter doctors by Governorate and City.

### 💳 Financials
*   **Stripe Integration:** Secure credit card processing.
*   **Payment Tracking:** Admin can view payment status (Paid/Unpaid/Failed).
*   **Revenue Reporting:** Exportable payment reports.

### 🔔 Notifications
*   **Email Alerts:** Automated emails for:
    *   New Appointment Bookings (to Doctor).
    *   Appointment Completion (to Patient).
    *   Welcome emails for new registrations.

---

## 5. System Navigation Guide

### For Administrators
*   **Dashboard:** Main overview.
*   **Doctors:** List, Add, Edit, Incomplete Profiles.
*   **Patients:** List, Add, Edit.
*   **Specialties:** Manage medical categories.
*   **Users:** Manage system users.
*   **Payments:** Financial overview.
*   **Settings:** General configuration.

### For Doctors
*   **My Profile:** Edit bio, fees, and photo.
*   **My Schedule:** Manage weekly working hours.
*   **Appointments:** View upcoming and past visits.
*   **Account Settings:** Change password.

### For Patients
*   **Home:** Search and featured doctors.
*   **My Profile:** Personal details.
*   **My Appointments:** History and upcoming bookings.
*   **Logout:** Secure exit.

---

## 6. Access & Usage

### 🔑 Access Control
The system enforces strict **Role-Based Access Control (RBAC)**. Users are redirected to their appropriate workspace upon login:
*   **Admins** $\rightarrow$ Redirected to `/dashboard`
*   **Doctors** $\rightarrow$ Redirected to `/doctors/profile`
*   **Patients** $\rightarrow$ Redirected to `/patients/profile` (or Home)

### 🛡️ Permissions Overview
*   **Public Access:** Homepage, Doctor Search, Doctor Details, Login/Register.
*   **Authenticated Access:** Booking appointments, Viewing personal profile.
*   **Restricted Access:**
    *   Only Admins can delete records.
    *   Only Doctors can see their specific patient list.
    *   Only Patients can see their own medical history.

### ⏱️ Session Handling
*   **Security:** Sessions are encrypted and managed via Redis for performance.
*   **Timeout:** Users are automatically logged out after periods of inactivity to protect patient data.
*   **Concurrent Access:** The system supports multiple concurrent users (Admins, Doctors, Patients) without data conflict.

---

## 7. Business Readiness Notes

### ✅ Ready for Deployment
*   **Core Workflows:** Registration, Booking, and Management flows are fully functional.
*   **Payment Gateway:** Stripe is integrated and ready for live keys.
*   **Localization:** Interface is fully translated to Arabic (RTL).

### ⚠️ Constraints & Considerations
*   **Language:** The system is primarily designed for Arabic users.
*   **Admin Route:** The admin panel is accessed via `/dashboard`, not `/admin`.
*   **Email:** Requires an SMTP server (e.g., Mailgun, SendGrid) for production email delivery.
*   **Timezones:** Schedules assume a single timezone operation.

---

## 8. Technical Specifications (High Level)
*   **Framework:** Laravel 10+ (PHP).
*   **Architecture:** Modular Monolith (Scalable & Maintainable).
*   **Database:** MySQL 8.0.
*   **Caching:** Redis (High performance).
*   **Containerization:** Fully Dockerized for easy deployment.
