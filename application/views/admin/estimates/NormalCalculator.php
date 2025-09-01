<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
    <script src="https://cdn.jsdelivr.net/jquery.circle-progress/1.2.2/circle-progress.min.js"></script>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>DB‑Driven Price Calculator</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
  body{font-family:Inter,system-ui,Arial;padding:24px;background:#f7f9fc;color:#111;}
  h1{margin:0 0 8px}
  .muted{color:#667;}
  .row{display:flex;gap:12px;flex-wrap:wrap;margin:16px 0}
  .card{background:#fff;border:1px solid #e7eaf0;border-radius:12px;padding:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)}
  .grid{display:grid;grid-template-columns:repeat(4,minmax(180px,1fr));gap:12px}
  label{font-size:12px;color:#445}
  select,input{width:100%;padding:10px;border-radius:10px;border:1px solid #cfd7e6;background:#fff}
  table{width:100%;border-collapse:separate;border-spacing:0;margin-top:16px}
  th,td{padding:10px 12px;border-bottom:1px solid #e8edf5;text-align:right}
  th:first-child,td:first-child{text-align:left}
  .pill{display:inline-flex;align-items:center;gap:8px;background:#f1f5ff;border:1px solid #cfe0ff;border-radius:999px;padding:6px 10px}
  .btn{padding:10px 14px;border-radius:10px;border:1px solid #cfd7e6;background:#0f62fe;color:#fff;cursor:pointer}
  .btn.secondary{background:#fff;color:#0f62fe}
  .stack{display:flex;gap:8px;align-items:center;justify-content:space-between;flex-wrap:wrap}
</style>
</head>
<body>
  <h1> Sheet Price Calculator</h1>
  

  <div class="card">
    <div class="grid" id="filters">
        
        <select id="clientid" name="clientid" class="form-control">
  <option value="">Select Customer</option>
  <?php foreach ($customer as $c): ?>
    <option value="<?= $c['userid']; ?>">
      <?= htmlspecialchars($c['company'] ?: $c['firstname'] . ' ' . $c['lastname']); ?>
    </option>
  <?php endforeach; ?>
</select>

      <div>
        <label>Colour</label>
        <select id="color">
  <option value="">Select Color</option>
  <?php foreach ($color as $c): ?>
    <option value="<?= $c['color_id']; ?>"><?= htmlspecialchars($c['color_name']); ?></option>
  <?php endforeach; ?>
</select>

      </div>
      <div>
        <label>Finish</label>
        <select id="finish">
  <option value="">Select Finish</option>
  <?php foreach ($finish as $c): ?>
    <option value="<?= $c['finish_code']; ?>"><?= htmlspecialchars($c['finish_name']); ?></option>
  <?php endforeach; ?>
</select>
      </div>
      <div>
        <label>Length (mm)</label>
       <select id="length">
  <option value="">Select Length</option>
  <?php foreach ($length as $l): ?>
    <option value="<?= $l; ?>"><?= $l; ?> mm</option>
  <?php endforeach; ?>
</select>

      </div>
      <div>
        <label>Thickness (mm)</label>
       <select id="thickness">
  <option value="">Select Thickness</option>
  <?php foreach ($thickness as $t): ?>
    <option value="<?= $t; ?>"><?= $t; ?> mm</option>
  <?php endforeach; ?>
</select>

      </div>
      <div>
  <label>Sheet Type</label>
  <select id="sheet_type">
    <option value="normal">Normal</option>
    <option value="standard">Standard</option>
    <option value="special">Special</option>
  </select>
</div>
<div>
  <label>Customer Group</label>
  <div id="customer_group_display" class="form-control" style="min-height: 38px; padding: 8px;">
    <!-- group name(s) will appear here -->
  </div>
</div>

    </div>
  </div>

  <div class="row">
    <div class="card" style="flex:1;min-width:320px">
      
      <div class="grid" style="grid-template-columns:repeat(3,1fr);margin-top:8px">
          <div>
  <label>Currency</label>
  <div style="display: flex; gap: 8px;">
    <select id="currency">
      <option value="USD">USD</option>
      <option value="EUR">EUR</option>
      <option value="GBP">GBP</option>
      <option value="JPY">JPY</option>
    </select>
    <input type="number" step="0.0001" id="purchased_rate" placeholder="Purchase Rate">
  </div>
</div>

         <div style="display: flex; gap: 8px;">
  <label>Shipping Cost</label>
  <input type="number" step="0.01" id="shipping_cost" placeholder="₹">
</div>

<div style="display: flex; gap: 8px;">
  <label>Additional Shipping</label>
  <input type="number" step="0.01" id="addl_shipping" placeholder="₹">
</div>

<div style="display: flex; gap: 8px;">
  <label>Weight (kg)</label>
  <input type="number" step="0.01" id="weight" placeholder="e.g. 12.5">
</div>

<div style="display: flex; gap: 8px;">
  <label> Margin based Constant %</label>
  <input type="number" step="0.01" id="const_margin" placeholder="%">
</div>
       <div id="margin_wrapper" style="display: flex; gap: 8px;">
  <label>Margin %</label>
  <input type="number" step="0.01" id="margin">
</div>

        <div style="display: flex; gap: 8px;"><label>Add. (Margin ). Market based %</label><input type="number" step="0.01" id="addl"></div>
        <div style="display: flex; gap: 8px;"><label>Base tier</label>
          <select id="baseTier">
            <option value="metal_market">Metal Market</option>
            <option value="satmat_purchase">SATMAT Purchase</option>
          </select>
        </div>
       

       
      </div>
      <div style="margin-top: 16px;">
  <button class="btn" onclick="calculatePrice()">Calculate</button>
</div>

    </div>

    <div class="card" style="flex:1;min-width:320px">
      <strong>Selected Item</strong>
      <table id="priceTable">
        <thead>
          <tr>
            <th>Field</th><th>Value</th>
          </tr>
        </thead>
        <tbody>
         
          
         <tr><td>Final Transport Cost</td><td id="transport">-</td></tr>


          
         
         
        </tbody>
      </table>
    </div>
  </div>


</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Format number to INR
  function formatINR(value) {
    return isNaN(value) ? '-' : '₹ ' + new Intl.NumberFormat('en-IN').format(Math.round(value));
  }

  function calculatePrice() {
    const purchaseCurrency = document.getElementById('currency').value;
    const purchaseRate = parseFloat(document.getElementById('purchased_rate').value || 0);
    const purchasePriceOriginal = parseFloat(prompt('Enter Purchase Price in ' + purchaseCurrency)) || 0;

    const convertedPriceINR = purchasePriceOriginal * purchaseRate;

    const margin = parseFloat(document.getElementById('margin').value || 0);
    const addl = parseFloat(document.getElementById('addl').value || 0);

    const basePrice = convertedPriceINR * (1 + (margin + addl) / 100);

    const pct_regular_hardware = parseFloat(document.getElementById('pct_regular_hardware').value || 0);
    const pct_hardware = parseFloat(document.getElementById('pct_hardware').value || 0);
    const pct_contractor = parseFloat(document.getElementById('pct_contractor').value || 0);
    const pct_architect = parseFloat(document.getElementById('pct_architect').value || 0);
    const pct_end_user = parseFloat(document.getElementById('pct_end_user').value || 0);

    const shippingCost = parseFloat(document.getElementById('shipping_cost').value || 0);
    const addlShipping = parseFloat(document.getElementById('addl_shipping').value || 0);
    const weight = parseFloat(document.getElementById('weight').value || 1); // Prevent divide by 0
    const constMargin = parseFloat(document.getElementById('const_margin').value || 0);

    // Transport cost per sheet
    const finalTransportCost = (shippingCost + addlShipping) / weight;
    const landedCost = convertedPriceINR + finalTransportCost;
    const finalSheetLandedCost = landedCost * (1 + constMargin / 100);

    const result = {
      reg_hw: basePrice * (1 + pct_regular_hardware / 100),
      hw: basePrice * (1 + pct_hardware / 100),
      contractor: basePrice * (1 + pct_contractor / 100),
      architect: basePrice * (1 + pct_architect / 100),
      end: basePrice * (1 + pct_end_user / 100)
    };

    // Set result in UI
    document.getElementById('reg_hw').textContent = formatINR(result.reg_hw);
    document.getElementById('hw').textContent = formatINR(result.hw);
    document.getElementById('contractor').textContent = formatINR(result.contractor);
    document.getElementById('architect').textContent = formatINR(result.architect);
    document.getElementById('end').textContent = formatINR(result.end);

    document.getElementById('transport').textContent = formatINR(finalTransportCost);
    document.getElementById('converted_inr').textContent = formatINR(convertedPriceINR);
    document.getElementById('landed').textContent = formatINR(landedCost);
    document.getElementById('sheet_landed').textContent = formatINR(finalSheetLandedCost);
  }

  // Sheet Type Change Logic
  document.getElementById('sheet_type').addEventListener('change', function () {
    const type = this.value;
    const marginWrapper = document.getElementById('margin_wrapper');
    if (type === 'standard') {
      marginWrapper.style.display = 'none';
      document.getElementById('margin').value = 0;
    } else {
      marginWrapper.style.display = 'flex';
    }
  });

  // Trigger margin check on load
  const st = document.getElementById('sheet_type');
  if (st) st.dispatchEvent(new Event('change'));

  // Bind button
  document.getElementById('calculate_btn')?.addEventListener('click', calculatePrice);
});
document.getElementById('clientid').addEventListener('change', function () {
  const clientId = this.value;
  const displayDiv = document.getElementById('customer_group_display');
  displayDiv.textContent = 'Loading...';

  if (!clientId) {
    displayDiv.textContent = '';
    return;
  }

  fetch('<?= admin_url('estimates/get_customer_group_ajax'); ?>/' + clientId)
    .then(res => res.json())
    .then(data => {
      if (data.success && data.groups.length > 0) {
        displayDiv.textContent = data.groups.join(', ');
      } else {
        displayDiv.textContent = 'No group assigned';
      }
    })
    .catch(() => {
      displayDiv.textContent = 'Error loading group';
    });
});
</script>
