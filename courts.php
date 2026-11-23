<?php
$page_title = "Courts & Forums | S&V Associates — Jurisdictions & Tribunals";
$page_description = "Overview of courts and specialized forums attended by S&V Associates across Kerala, including High Court of Kerala, District Courts, Criminal Courts, MACT, and Family Courts.";

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-container">
    
    <!-- Header Strip -->
    <div class="page-header-strip">
        <div>
            <h1 class="page-title-main">Courts &amp; Forums</h1>
            <div class="page-breadcrumb-sub">Home / Courts &amp; Forums</div>
        </div>
        <div class="page-header-thumb">
            <img src="assets/images/high-court.jpg" alt="High Court of Kerala">
        </div>
    </div>

    <!-- Interactive Jurisdictional Grid -->
    <div class="courts-interactive-canvas">
        
        <!-- 1. Top Left: Appellate & Constitutional (①) -->
        <div class="court-node-box" style="grid-column:1; grid-row:1;">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18"/><line x1="10" x2="10" y1="18"/><line x1="14" x2="14" y1="18"/><line x1="18" x2="18" y1="18"/><polygon points="12 2 20 7 4 7"/>
            </svg>
            <div>
                <div class="court-node-title">Appellate &amp; Constitutional</div>
                <div class="court-node-desc">High Court of Kerala (Writ Petitions 226/227), Matrimonial, Civil, Criminal and MACT Appeals.</div>
            </div>
        </div>

        <!-- 2. Top Right: Civil Courts (②) -->
        <div class="court-node-box" style="grid-column:3; grid-row:1;">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10"/><path d="M12 3v18"/>
            </svg>
            <div>
                <div class="court-node-title">Civil Courts</div>
                <div class="court-node-desc">District &amp; Additional District Courts, Subordinate Courts, and Munsiff Courts.</div>
            </div>
        </div>

        <!-- Center Hexagon / Shield Emblem -->
        <div class="courts-center-hexagon" style="grid-column:2; grid-row:1 / span 2;">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/>
            </svg>
            <p class="courts-center-p">
                We represent clients before the High Court, civil and criminal jurisdictions, and specialized tribunals across Kerala.
            </p>
        </div>

        <!-- 3. Mid Left: Criminal Jurisdictions (③) -->
        <div class="court-node-box" style="grid-column:1; grid-row:2;">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <circle cx="6" cy="18" r="4"/><circle cx="18" cy="18" r="4"/><path d="M10 18h4"/><path d="M6 14V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8"/>
            </svg>
            <div>
                <div class="court-node-title">Criminal Jurisdictions</div>
                <div class="court-node-desc">District &amp; Sessions Courts, Judicial First Class Magistrate Courts (JFCM), and Fast Track Special Courts (POCSO), NDPS.</div>
            </div>
        </div>

        <!-- 4. Mid Right: Motor Accident Claims Tribunal (MACT) (④) -->
        <div class="court-node-box" style="grid-column:3; grid-row:2;">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C2.1 10.9 2 11.2 2 11.5V16c0 .6.4 1 1 1h2"/>
                <circle cx="7" cy="17" r="2"/>
                <path d="M9 17h6"/>
                <circle cx="17" cy="17" r="2"/>
            </svg>
            <div>
                <div class="court-node-title">Motor Accident Claims Tribunal (MACT)</div>
                <div class="court-node-desc">Claims adjudication, compensation assessments, and liability determinations across Motor Accidents Claims Tribunals.</div>
            </div>
        </div>

        <!-- 5. Bottom Row: Family Courts (⑤) -->
        <div class="court-node-box" style="grid-column:1 / -1; grid-row:3;">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <div>
                <div class="court-node-title">Family Courts</div>
                <div class="court-node-desc">Matrimonial disputes, divorce proceedings, maintenance, child custody, guardianship, and domestic violence matters across Family Courts.</div>
            </div>
        </div>

    </div>

</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
