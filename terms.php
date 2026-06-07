<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms & Conditions | Morogoro Taste Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --main-green: #19a463;
            --main-green-dark: #14834e;
            --main-bg: #f5f6fa;
        }
        body {
            background: linear-gradient(135deg, #e6f7ee 0%, #f9fafb 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #1a1a1a;
        }
        .terms-container {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px 0 rgba(25,164,99,0.13), 0 1.5px 6px 0 rgba(0,0,0,0.05);
            padding: 3rem 2.5rem;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .terms-header {
            text-align: center;
            margin-bottom: 2.5rem;
            border-bottom: 2px solid var(--main-green);
            padding-bottom: 1.5rem;
        }
        .terms-header h1 {
            color: var(--main-green-dark);
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        .terms-header p {
            color: #666;
            font-size: 1.05rem;
        }
        .terms-content {
            line-height: 1.8;
            color: #333;
        }
        .terms-content h2 {
            color: var(--main-green);
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-size: 1.4rem;
            border-left: 4px solid var(--main-green);
            padding-left: 0.8rem;
        }
        .terms-content h3 {
            color: var(--main-green-dark);
            font-weight: 600;
            margin-top: 1.2rem;
            margin-bottom: 0.6rem;
            font-size: 1.1rem;
        }
        .terms-content p {
            margin-bottom: 1rem;
            text-align: justify;
        }
        .terms-content ul, .terms-content ol {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .terms-content li {
            margin-bottom: 0.5rem;
        }
        .terms-footer {
            text-align: center;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid #e0e5e2;
        }
        .back-btn {
            background: linear-gradient(90deg, var(--main-green) 60%, var(--main-green-dark) 100%);
            color: #fff !important;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 2px 12px rgba(25,164,99,0.3);
        }
        .back-btn:hover {
            box-shadow: 0 5px 18px rgba(25,164,99,0.4);
            transform: translateY(-2px);
        }
        .highlight {
            background: #e8fff3;
            padding: 1.5rem;
            border-left: 4px solid var(--main-green);
            border-radius: 4px;
            margin: 1.5rem 0;
        }
        .highlight strong {
            color: var(--main-green);
        }
        @media (max-width: 768px) {
            .terms-container {
                padding: 1.5rem 1rem;
                margin-top: 15px;
                margin-bottom: 15px;
            }
            .terms-header h1 {
                font-size: 1.6rem;
            }
            .terms-content h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="container" style="max-width: 900px;">
    <div class="terms-container">
        <div class="terms-header">
            <h1><i class="bi bi-file-earmark-text"></i> Terms & Conditions</h1>
            <p>Morogoro Taste Food - Food Delivery Platform</p>
        </div>

        <div class="terms-content">
            <h2>1. Introduction & Acceptance</h2>
            <p>
                Welcome to <strong>Morogoro Taste Food</strong>. These Terms & Conditions govern your use of our platform, website, and services. By accessing, browsing, or using our service, you acknowledge that you have read, understood, and agree to be bound by these terms. If you do not agree, please do not use our platform.
            </p>

            <h2>2. Service Description</h2>
            <p>
                Morogoro Taste Food is an online food delivery platform that connects customers with partner restaurants and food vendors. We facilitate the ordering and delivery of food items. We are not the direct supplier of food—we are an intermediary connecting you with restaurants.
            </p>

            <h2>3. User Eligibility</h2>
            <ul>
                <li>You must be at least 18 years old to create an account and place orders.</li>
                <li>You agree that the information you provide during registration is accurate and complete.</li>
                <li>You are responsible for maintaining the confidentiality of your login credentials.</li>
                <li>Any orders placed using your account are your responsibility, whether placed by you or someone with access to your credentials.</li>
            </ul>

            <h2>4. Account & Registration</h2>
            <ul>
                <li>You agree to provide valid, accurate contact information when creating an account.</li>
                <li>You must keep your password secure and immediately notify us of any unauthorized access.</li>
                <li>We reserve the right to suspend or delete accounts that violate these terms or provide false information.</li>
                <li>Account suspension may result from inappropriate behavior, payment defaults, or fraudulent activities.</li>
            </ul>

            <h2>5. Ordering & Payment</h2>
            <h3>Order Placement:</h3>
            <ul>
                <li>All orders are subject to acceptance by both the restaurant and Morogoro Taste Food.</li>
                <li>We reserve the right to cancel or refuse any order at our discretion.</li>
                <li>Prices displayed are subject to change without notice, though we strive for accuracy.</li>
            </ul>

            <h3>Payment Methods:</h3>
            <ul>
                <li>We accept various payment methods including Cash, M-Pesa, Tigo Pesa, Airtel Money, HaloPesa, and Bank Cards.</li>
                <li>All transactions are conducted securely.</li>
                <li>You authorize us to charge your selected payment method for the order total.</li>
                <li>For mobile money payments, you are responsible for ensuring sufficient funds before confirming your order.</li>
            </ul>

            <h3>Pricing:</h3>
            <ul>
                <li>The total amount displayed includes food subtotal, delivery fee, taxes (if applicable), and any applied discounts.</li>
                <li>Promotional codes can only be used once unless otherwise stated.</li>
                <li>Refunds for promotional codes are not provided; they are non-transferable.</li>
            </ul>

            <h2>6. Delivery Terms</h2>
            <ul>
                <li><strong>Delivery Address:</strong> You must provide a valid, accessible delivery address in Morogoro region.</li>
                <li><strong>Estimated Time:</strong> Delivery times shown are estimates only. We are not liable for delays caused by traffic, weather, or other unforeseen circumstances.</li>
                <li><strong>Delivery Acceptance:</strong> By accepting delivery, you confirm that the order is complete, undamaged, and meets your expectations.</li>
                <li><strong>Failed Delivery:</strong> If you are not available at the delivery address, the delivery agent may leave the order at a safe location or attempt redelivery. We are not responsible for unclaimed orders.</li>
            </ul>

            <h2>7. Product Quality & Liability</h2>
            <div class="highlight">
                <strong>Important:</strong> Restaurants are responsible for food quality, preparation, and compliance with health standards. Morogoro Taste Food is a delivery platform only and does not prepare or quality-control food items.
            </div>
            <ul>
                <li>We recommend you inspect food items immediately upon delivery.</li>
                <li>If you receive damaged, incorrect, or spoiled items, report within 1 hour of delivery.</li>
                <li>Claims beyond 1 hour will not be entertained.</li>
                <li>We will work with the restaurant to resolve legitimate food quality issues.</li>
            </ul>

            <h2>8. Allergies & Dietary Requirements</h2>
            <p>
                <strong>Critical Notice:</strong> If you have allergies or specific dietary requirements, you must:
            </p>
            <ul>
                <li>Clearly specify them in the order notes section.</li>
                <li>Contact the restaurant directly before placing your order if you have severe allergies.</li>
                <li>Understand that restaurants may handle allergens in their kitchens despite your instructions.</li>
                <li>Morogoro Taste Food is not liable for allergic reactions resulting from non-disclosure or mishandling of allergen information.</li>
            </ul>

            <h2>9. Cancellations & Refunds</h2>
            <h3>Cancellation by Customer:</h3>
            <ul>
                <li>You may cancel your order within 5 minutes of placement.</li>
                <li>Cancellations after 5 minutes depend on restaurant preparation status and may incur a 10% cancellation fee.</li>
                <li>Once a delivery driver is assigned, cancellation is not possible; the order must be completed or returned for a full refund minus any delivery fees.</li>
            </ul>

            <h3>Refunds:</h3>
            <ul>
                <li>Approved refunds will be processed to your original payment method within 3-7 business days.</li>
                <li>Promotional credit refunds are not provided; credits are non-refundable.</li>
            </ul>

            <h2>10. User Conduct</h2>
            <p>You agree NOT to:</p>
            <ul>
                <li>Use the platform for illegal activities or in violation of local laws.</li>
                <li>Harass, abuse, or threaten restaurant staff or delivery personnel.</li>
                <li>Submit false claims regarding order issues to obtain fraudulent refunds.</li>
                <li>Resell products ordered through our platform for commercial gain.</li>
                <li>Attempt to manipulate promotional offers or exploit system vulnerabilities.</li>
                <li>Share your account credentials with others.</li>
            </ul>

            <h2>11. Privacy & Data Protection</h2>
            <p>
                Your personal data is collected and processed according to our <strong>Privacy Policy</strong>. By using our service, you consent to the collection and use of your information as outlined in our Privacy Policy.
            </p>

            <h2>12. Limitation of Liability</h2>
            <div class="highlight">
                <strong>Disclaimer:</strong> Morogoro Taste Food operates as an intermediary and is NOT liable for:
                <ul style="margin-bottom: 0;">
                    <li>Food quality, freshness, or allergen content (restaurant's responsibility)</li>
                    <li>Delivery delays due to external factors (traffic, weather, security incidents)</li>
                    <li>Third-party actions (restaurant staff, delivery personnel)</li>
                    <li>Indirect or consequential damages from service disruption</li>
                    <li>Issues arising from incorrect delivery address or unavailability at delivery time</li>
                </ul>
            </div>

            <h2>13. Dispute Resolution</h2>
            <ul>
                <li>Any disputes arising from orders must be reported within 24 hours of delivery.</li>
                <li>We will investigate and attempt to resolve disputes amicably.</li>
                <li>If unresolved, disputes may be referred to Tanzanian consumer protection authorities.</li>
                <li>These terms are governed by the laws of Tanzania.</li>
            </ul>

            <h2>14. Changes to Terms</h2>
            <p>
                Morogoro Taste Food reserves the right to modify these Terms & Conditions at any time. Changes will be posted on our website, and your continued use of the platform constitutes acceptance of the new terms.
            </p>

            <h2>15. Contact & Support</h2>
            <p>
                For questions, disputes, or concerns regarding these terms or our service, please contact us at:
            </p>
            <ul>
                <li><strong>Email:</strong> support@morogorotastefood.co.tz</li>
                <li><strong>Phone:</strong> +255 XXXX XXX XXX</li>
                <li><strong>Address:</strong> Morogoro, Tanzania</li>
            </ul>

            <h2>16. Severability</h2>
            <p>
                If any provision of these Terms is found to be unenforceable, the remaining provisions will continue in effect to the maximum extent permitted by law.
            </p>

            <h2>17. Entire Agreement</h2>
            <p>
                These Terms & Conditions, together with our Privacy Policy, constitute the entire agreement between you and Morogoro Taste Food regarding your use of our platform.
            </p>
        </div>

        <div class="terms-footer">
            <p style="color: #666; margin-bottom: 1.5rem;">
                <strong>Last Updated:</strong> <?= date('F j, Y') ?>
            </p>
            <a href="javascript:window.close();" class="back-btn" onclick="if(window.history.length > 1) window.history.back(); else window.close(); return false;">
                <i class="bi bi-arrow-left"></i> Go Back
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
