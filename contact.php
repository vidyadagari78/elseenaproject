<?php
require_once __DIR__ . '/includes/config.php';

$feedback = null;
$feedback_type = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $preferred_office = trim($_POST['preferred_office'] ?? '');
    $practice_area = trim($_POST['practice_area'] ?? '');
    $subject_overview = trim($_POST['subject_overview'] ?? '');
    $bci_consent = isset($_POST['bci_consent']) ? true : false;

    // Validation
    if (empty($full_name) || empty($phone) || empty($email) || empty($preferred_office) || empty($practice_area) || empty($subject_overview)) {
        $feedback = "Please fill in all required fields.";
        $feedback_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "Please enter a valid email address.";
        $feedback_type = "error";
    } elseif (!$bci_consent) {
        $feedback = "You must acknowledge the compliance checkbox regarding the advocate-client relationship before submitting.";
        $feedback_type = "error";
    } else {
        // Target notification recipient
        $to = defined('PRIMARY_EMAIL') ? PRIMARY_EMAIL : 'svlegalassociates2026@gmail.com';
        $subject = "New Legal Enquiry [" . $practice_area . "] - " . $full_name;

        // Professional HTML Email Body
        $sender_host = $_SERVER['SERVER_NAME'] ?? 'svassociates.in';
        if ($sender_host === 'localhost' || $sender_host === '127.0.0.1') {
            $sender_host = 'svassociates.in';
        }
        
        $html_body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f6f3eb; margin: 0; padding: 24px; color: #1e293b; }
                .email-card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
                .header-bar { background: #711326; color: #ffffff; padding: 22px 24px; text-align: center; }
                .header-bar h1 { margin: 0; font-size: 20px; letter-spacing: 1.5px; font-weight: 700; }
                .header-bar p { margin: 4px 0 0; font-size: 13px; color: #fce7f3; }
                .content-body { padding: 24px; }
                .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                .info-table td { padding: 10px 12px; font-size: 14px; border-bottom: 1px solid #f1f5f9; }
                .info-table td.label { font-weight: 700; color: #475569; width: 35%; background: #f8fafc; }
                .info-table td.value { color: #0f172a; font-weight: 500; }
                .overview-box { background: #fdfaf6; border-left: 4px solid #711326; padding: 14px 16px; border-radius: 4px; font-size: 14px; line-height: 1.6; color: #334155; margin-bottom: 20px; }
                .compliance-badge { background: #f1f5f9; padding: 12px 14px; border-radius: 4px; font-size: 12px; color: #64748b; line-height: 1.5; }
                .footer-bar { background: #0f1216; color: #94a3b8; font-size: 11px; text-align: center; padding: 14px 20px; }
            </style>
        </head>
        <body>
            <div class='email-card'>
                <div class='header-bar'>
                    <h1>S&amp;V ASSOCIATES</h1>
                    <p>New Client Website Enquiry</p>
                </div>
                <div class='content-body'>
                    <table class='info-table'>
                        <tr><td class='label'>Client Name</td><td class='value'>" . htmlspecialchars($full_name) . "</td></tr>
                        <tr><td class='label'>Phone / WhatsApp</td><td class='value'><a href='tel:" . htmlspecialchars($phone) . "'>" . htmlspecialchars($phone) . "</a></td></tr>
                        <tr><td class='label'>Email Address</td><td class='value'><a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></td></tr>
                        <tr><td class='label'>Preferred Chamber</td><td class='value'>" . htmlspecialchars($preferred_office) . "</td></tr>
                        <tr><td class='label'>Practice Area</td><td class='value'>" . htmlspecialchars($practice_area) . "</td></tr>
                        <tr><td class='label'>Submitted Date &amp; Time</td><td class='value'>" . date('d M Y, h:i A') . " IST</td></tr>
                    </table>

                    <div style='font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #711326; margin-bottom: 6px;'>
                        Matter / Subject Overview:
                    </div>
                    <div class='overview-box'>
                        " . nl2br(htmlspecialchars($subject_overview)) . "
                    </div>

                    <div class='compliance-badge'>
                        <strong>Compliance Note:</strong> The client acknowledged that submission of this enquiry form does not constitute an advocate-client relationship under BCI Rule 36.
                    </div>
                </div>
                <div class='footer-bar'>
                    S&amp;V Associates Advocates &amp; Legal Consultants &bull; Ernakulam &amp; North Paravur &bull; Official Website
                </div>
            </div>
        </body>
        </html>";

        // Dispatch Email Headers
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: S&V Associates Enquiry <no-reply@' . $sender_host . '>';
        $headers[] = 'Reply-To: ' . $full_name . ' <' . $email . '>';
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        // Attempt delivery via PHP mail()
        $mail_sent = @mail($to, $subject, $html_body, implode("\r\n", $headers));

        // Save enquiry with Vercel serverless write fallback
        $dataDir = __DIR__ . '/data';
        if (!is_dir($dataDir)) {
            @mkdir($dataDir, 0777, true);
        }
        $dataFile = is_writable($dataDir) || !file_exists($dataFile = $dataDir . '/enquiries.json') ? $dataDir . '/enquiries.json' : sys_get_temp_dir() . '/enquiries.json';
        $existing = [];
        if (file_exists($dataFile)) {
            $json = @file_get_contents($dataFile);
            $existing = json_decode($json, true) ?: [];
        }
        $existing[] = [
            'id' => uniqid('enq_'),
            'timestamp' => date('Y-m-d H:i:s'),
            'full_name' => $full_name,
            'phone' => $phone,
            'email' => $email,
            'preferred_office' => $preferred_office,
            'practice_area' => $practice_area,
            'subject_overview' => $subject_overview,
            'recipient_email' => $to,
            'mail_dispatched' => $mail_sent,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'
        ];
        @file_put_contents($dataFile, json_encode($existing, JSON_PRETTY_PRINT));

        $feedback = "Thank you, " . htmlspecialchars($full_name) . ". Your enquiry has been submitted. Our office will contact you shortly.";
        $feedback_type = "success";
    }
}

$page_title = "Contact Us | S&V Associates — Ernakulam & North Paravur Chambers";
$page_description = "Contact S&V Associates at our High Court chambers in Ernakulam or North Paravur office. Schedule a formal consultation for legal representation across Kerala.";

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-container">
    
    <!-- Header Strip -->
    <div class="page-header-strip">
        <div>
            <h1 class="page-title-main">Contact Us</h1>
            <div class="page-breadcrumb-sub">Home / Contact</div>
        </div>
    </div>

    <div class="contact-layout-split">
        
        <!-- Left Column: Our Offices -->
        <div class="offices-column">
            <div class="about-section-label">Our Offices</div>

            <!-- Card 1: Ernakulam Office -->
            <div class="office-dual-card">
                <div class="office-thumb-sm">
                    <img src="assets/images/office-ernakulam.jpg" alt="Ernakulam Office">
                </div>
                <div class="office-body-sm">
                    <h3>ERNAKULAM OFFICE</h3>
                    <p>3rd Floor, M. M. Mathew Building, Opposite Gate No. 2, High Court of Kerala, Ernakulam, Kerala</p>
                    <div class="office-phone-pill">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>+91 9633552910 / +91 9947782842</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: North Paravur Office -->
            <div class="office-dual-card">
                <div class="office-thumb-sm">
                    <img src="assets/images/office-paravur.jpg" alt="North Paravur Office">
                </div>
                <div class="office-body-sm">
                    <h3>NORTH PARAVUR OFFICE</h3>
                    <p>1st Floor, Municipal Shopping Complex, Opposite Punjab National Bank, Main Road, North Paravur, Ernakulam – 683513, Kerala</p>
                    <div class="office-phone-pill">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>+91 9847093010 / +91 9447292616 /+91 9633552910 / +91 9947782842</span>
                    </div>
                </div>
            </div>

            <!-- Bottom 3 Info Strip -->
            <div class="contact-quick-strip">
                <div class="quick-comm-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    <div>
                        <div class="quick-label">EMAIL</div>
                        <div class="quick-val"><?php echo PRIMARY_EMAIL; ?></div>
                    </div>
                </div>

                <div class="quick-comm-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    <div>
                        <div class="quick-label">WHATSAPP</div>
                        <div class="quick-val">+91 9633552910</div>
                    </div>
                </div>

                <div class="quick-comm-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <div>
                        <div class="quick-label">TIMINGS</div>
                        <div class="quick-val">Mon - Sat: 9:00 - 7:00</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Send Us an Enquiry Form -->
        <div>
            <div class="enquiry-white-card">
                <h2>Send Us an Enquiry</h2>

                <?php if ($feedback): ?>
                    <div style="padding:10px 14px; border-radius:4px; font-size:0.8rem; margin-bottom:14px; background:<?php echo $feedback_type === 'success' ? '#dcfce7' : '#fee2e2'; ?>; color:<?php echo $feedback_type === 'success' ? '#166534' : '#991b1b'; ?>;">
                        <?php echo htmlspecialchars($feedback); ?>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST" id="enquiryForm">
                    
                    <div class="form-grid-2">
                        <div class="form-cell">
                            <label class="form-cell-label" for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" class="form-input" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                        </div>
                        <div class="form-cell">
                            <label class="form-cell-label" for="phone">Contact / WhatsApp</label>
                            <input type="tel" id="phone" name="phone" class="form-input" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-cell">
                            <label class="form-cell-label" for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>
                        <div class="form-cell">
                            <label class="form-cell-label" for="preferred_office">Preferred Office</label>
                            <select id="preferred_office" name="preferred_office" class="form-input" required>
                                <option value="" disabled <?php echo empty($_POST['preferred_office']) ? 'selected' : ''; ?>></option>
                                <option value="Ernakulam Office (High Court)" <?php echo (($_POST['preferred_office'] ?? '') === 'Ernakulam Office (High Court)') ? 'selected' : ''; ?>>Ernakulam Office</option>
                                <option value="North Paravur Office" <?php echo (($_POST['preferred_office'] ?? '') === 'North Paravur Office') ? 'selected' : ''; ?>>North Paravur Office</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-cell" style="margin-bottom:12px;">
                        <label class="form-cell-label" for="practice_area">Practice Area</label>
                        <select id="practice_area" name="practice_area" class="form-input" required>
                            <option value="" disabled <?php echo empty($_POST['practice_area']) ? 'selected' : ''; ?>></option>
                            <option value="Motor Accident Claims (MACT)">Motor Accident Claims (MACT)</option>
                            <option value="Civil Litigation">Civil Litigation</option>
                            <option value="Criminal Law">Criminal Law</option>
                            <option value="Family & Matrimonial">Family &amp; Matrimonial</option>
                            <option value="High Court & Appellate">High Court &amp; Appellate</option>
                            <option value="Commercial & Drafting">Commercial &amp; Drafting</option>
                            <option value="General Enquiry">General Enquiry</option>
                        </select>
                    </div>

                    <div class="form-cell">
                        <label class="form-cell-label" for="subject_overview">Subject &amp; Brief Overview</label>
                        <textarea id="subject_overview" name="subject_overview" class="form-input" required><?php echo htmlspecialchars($_POST['subject_overview'] ?? ''); ?></textarea>
                    </div>

                    <div class="checkbox-consent-row">
                        <input type="checkbox" id="bci_consent" name="bci_consent" value="1" required <?php echo isset($_POST['bci_consent']) ? 'checked' : ''; ?>>
                        <label for="bci_consent">
                            I understand that submitting this enquiry does not create an advocate-client relationship.
                        </label>
                    </div>

                    <button type="submit" class="btn-burgundy-block">
                        <span>SUBMIT ENQUIRY</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>

                </form>
            </div>
        </div>

    </div>

    <!-- Very bottom dual location label strip matching Panel 6 -->
    <div class="contact-bottom-offices">
        <div class="contact-bottom-item">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            <div><strong>Ernakulam Office:</strong> 3rd Floor, M. M. Mathew Building, Opp. Gate No. 2, High Court of Kerala, Ernakulam</div>
        </div>
        <div class="contact-bottom-item">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            <div><strong>North Paravur Office:</strong> 1st Floor, Municipal Shopping Complex, Opp. Punjab National Bank, Main Road, North Paravur</div>
        </div>
    </div>

</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
