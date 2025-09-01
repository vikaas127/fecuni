<script>
  let hraIndex = 0, loanIndex = 0, investmentIndex = 0;
let removedHRA = [], removedLoan = [], removedInvestment = [];

function countRealGroups(containerSelector, className) {
  return Array.from(document.querySelectorAll(`${containerSelector} .${className}`))
    .filter(group => !group.closest('template') && group.querySelector('input, select, textarea')).length;
}

function add_edit_investments() {
  $('#investments').modal('show');
  $('.edit-title').removeClass('hide');
  $('.add-title').addClass('hide');

  hraIndex = countRealGroups('#hra-container', 'hra-group');
  loanIndex = countRealGroups('#loan-container', 'loan-group');
  investmentIndex = countRealGroups('#investment-container', 'investment-group');

  removedHRA = [];
  removedLoan = [];
  removedInvestment = [];

  $('#removed_hra_ids').val('');
  $('#removed_loan_ids').val('');
  $('#removed_investment_ids').val('');

  setTimeout(() => {
    $('select[name^="investments"][name$="[section]"]').each(function () {
      updateTypes(this);
    });
    updateRentalMonthInputs();
  }, 300);
}

$(document).ready(function () {
  toggleHRA();
  toggleLoan();
  toggleInvestment();
  updateRentalMonthInputs();
});

// ----------- TOGGLE SECTIONS ------------
function toggleHRA() {
  const select = document.getElementById('declares_hra');
  const wrapper = document.getElementById('hra-wrapper');
  const container = document.getElementById('hra-container');
  if (!select || !wrapper || !container) return;

  wrapper.style.display = select.value === 'Yes' ? 'block' : 'none';

  if (select.value === 'No') {
    removedHRA = [];
    container.innerHTML = '';
    $('#removed_hra_ids').val('');
  } else if (!container.querySelector('.hra-group')) {
    addHRA();
  }
}

function toggleLoan() {
  const select = document.getElementById('declares_home_loan');
  const wrapper = document.getElementById('loan-wrapper');
  const container = document.getElementById('loan-container');
  if (!select || !wrapper || !container) return;

  wrapper.style.display = select.value === 'Yes' ? 'block' : 'none';

  if (select.value === 'No') {
    removedLoan = [];
    container.innerHTML = '';
    $('#removed_loan_ids').val('');
  } else if (!container.querySelector('.loan-group')) {
    addLoan();
  }
}

function toggleInvestment() {
  const wrapper = document.getElementById('investment-wrapper');
  const container = document.getElementById('investment-container');
  if (!wrapper || !container) return;

  wrapper.style.display = 'block';

  if (!container.querySelector('.investment-group')) {
    addInvestment();
  }
}

// ------------ ADD / REMOVE FUNCTIONS ----------
function removeGroup(button, type) {
  const group = button.closest(`.${type}-group`);
  const id = group.getAttribute('data-id');

  if (id) {
    if (type === 'hra') removedHRA.push(id);
    if (type === 'loan') removedLoan.push(id);
    if (type === 'investment') removedInvestment.push(id);
  }

  group.remove();

  $('#removed_hra_ids').val(removedHRA.join(','));
  $('#removed_loan_ids').val(removedLoan.join(','));
  $('#removed_investment_ids').val(removedInvestment.join(','));

  updateRentalMonthInputs();
}

function addHRA() {
  const container = document.getElementById('hra-container');
  const template = document.getElementById('hra-template');
  if (!template || !container) return;

  const html = template.innerHTML.replace(/__index__/g, hraIndex);
  container.insertAdjacentHTML('beforeend', html);
  hraIndex++;

  updateRentalMonthInputs();
}

function addLoan() {
  const container = document.getElementById('loan-container');
  const template = document.getElementById('loan-template');
  if (!template || !container) return;

  const html = template.innerHTML.replace(/__index__/g, loanIndex);
  container.insertAdjacentHTML('beforeend', html);
  loanIndex++;
}

function addInvestment() {
  const container = document.getElementById('investment-container');
  const template = document.getElementById('investment-template');
  if (!template || !container) return;

  const html = template.innerHTML.replace(/__index__/g, investmentIndex);
  container.insertAdjacentHTML('beforeend', html);

  const lastGroup = container.querySelector('.investment-group:last-child');
  const newSection = lastGroup.querySelector('select[name*="[section]"]');
  if (newSection) {
    newSection.addEventListener('change', function () {
      updateTypes(this);
    });
  }

  investmentIndex++;
}

// ---------- RENTAL MONTH CALENDAR ----------
function getAllowedMonths() {
  const now = new Date();
  const month = now.getMonth() + 1;
  const year = now.getFullYear();
  const startYear = month >= 4 ? year : year - 1;
  const endYear = month >= 4 ? year + 1 : year;

  const allowed = [];
  for (let i = 4; i <= 12; i++) {
    allowed.push(`${startYear}-${String(i).padStart(2, '0')}`);
  }
  for (let i = 1; i <= 3; i++) {
    allowed.push(`${endYear}-${String(i).padStart(2, '0')}`);
  }
  return allowed;
}

function updateRentalMonthInputs() {
  const allowed = getAllowedMonths();

  $('input[type="month"][name^="hra"][name$="[rental_from]"], input[type="month"][name^="hra"][name$="[rental_to]"]').each(function () {
    const $input = $(this);
    const currentVal = $input.val();

    $input.attr('min', allowed[0]);
    $input.attr('max', allowed[allowed.length - 1]);

    if (currentVal && !allowed.includes(currentVal)) {
      $input.val('');
    }
  });
}

// ---------- SECTION TYPE LOADER -------------
function getSelectedInvestmentTypes() {
  const selected = [];
  document.querySelectorAll('select[name*="[investment_type]"]').forEach(select => {
    if (select.value) selected.push(select.value);
  });
  return selected;
}

function updateTypes(select) {
  const section = $(select).val();
  const $row = $(select).closest('.row');
  const $typeSelect = $row.find('select[name*="investment_type"]');

  let selectedValue = $typeSelect.data('selected');
  if (!selectedValue) selectedValue = $typeSelect.val();

  $typeSelect.find('option:not(:first)').remove();
  if (!section) return;

  $.ajax({
    url: admin_url + 'hr_profile/get_investment_types_by_section/' + section,
    method: 'GET',
    dataType: 'json',
    success: function (data) {
      if (Array.isArray(data)) {
        const selectedTypes = getSelectedInvestmentTypes();
        let matched = false;

        data.forEach(function (item) {
          const isSelected = item === selectedValue;
          const isDisabled = selectedTypes.includes(item) && !isSelected;
          const disabledAttr = isDisabled ? 'disabled' : '';
          if (isSelected) matched = true;

          $typeSelect.append(`<option value="${item}" ${isSelected ? 'selected' : ''} ${disabledAttr}>${item}</option>`);
        });

        if (!matched && selectedValue) {
          $typeSelect.append(`<option value="${selectedValue}" selected>${selectedValue}</option>`);
        }

        $typeSelect.removeAttr('data-selected');
      }
    },
    error: function () {
      alert('Error loading investment types.');
    }
  });
}

// ---------- FINAL FORM VALIDATION ----------
$('investmentsForm').on('submit', function (e) {
  let formValid = true;
  const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]$/;
  const usedMonths = {};
  const seenPairs = new Set();

  $('#hra-container .hra-group').each(function () {
    const $group = $(this);

    const rent = parseFloat($group.find('input[name^="hra"][name$="[monthly_rent]"]').val()) || 0;
    const from = $group.find('input[name^="hra"][name$="[rental_from]"]').val();
    const to = $group.find('input[name^="hra"][name$="[rental_to]"]').val();
    const panInput = $group.find('input[name^="hra"][name$="[landlord_pan]"]')[0];

    panInput.setCustomValidity('');

    if (!from || !to) {
      formValid = false;
      alert('Please select both Rental From and Rental To dates.');
      e.preventDefault();
      return false;
    }

    const start = new Date(from + '-01');
    const end = new Date(to + '-01');

    if (end < start) {
      formValid = false;
      alert('Rental To date cannot be before Rental From.');
      e.preventDefault();
      return false;
    }

    const monthRangeKey = `${from}_${to}`;
    if (seenPairs.has(monthRangeKey)) {
      formValid = false;
      alert('Duplicate rental period detected.');
      e.preventDefault();
      return false;
    }
    seenPairs.add(monthRangeKey);

    const temp = new Date(start);
    while (temp <= end) {
      const key = `${temp.getFullYear()}-${String(temp.getMonth() + 1).padStart(2, '0')}`;
      if (usedMonths[key]) {
        formValid = false;
        alert('Rental months must not overlap across HRA entries.');
        e.preventDefault();
        return false;
      }
      usedMonths[key] = true;
      temp.setMonth(temp.getMonth() + 1);
    }

    const months = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start.getMonth()) + 1;
    const annualRent = rent * months;
    const pan = panInput.value.trim().toUpperCase();

    if (annualRent > 100000) {
      if (!pan) {
        panInput.setCustomValidity('PAN is required as annual rent exceeds ₹1,00,000.');
        panInput.reportValidity();
        formValid = false;
        e.preventDefault();
        return false;
      } else if (!panRegex.test(pan)) {
        panInput.setCustomValidity('Invalid PAN format. Use 5 letters, 4 digits, 1 letter (e.g., ABCDE1234F).');
        panInput.reportValidity();
        formValid = false;
        e.preventDefault();
        return false;
      }
    }
  });

  if (!formValid) {
    return false;
  }
});

</script>
<script>
   
function showTaxModal(staffId, declarationId, financialYear = '') {
    if (!declarationId) {
        alert("Declaration not found.");
        return;
    }

    // Set hidden inputs
    $('#tax_employee_id').val(staffId);
    $('#tax_declaration_id').val(declarationId);
    $('#tax_financial_year').val(financialYear);

    // Show modal
    $('#taxModal').modal('show');

    // Fetch tax data
    $.ajax({
        url: admin_url + 'hr_profile/view_tax',
        method: 'POST',
        data: {
            employee_id: staffId,
            declaration_id: declarationId,
            financial_year: financialYear
        },
        success: function (res) {
            try {
                if (typeof res === "string") {
                    res = JSON.parse(res); // Parse string to JSON
                }
            } catch (e) {
                $('#taxCalculationContent').html('<div class="alert alert-danger">Invalid JSON returned from server.</div>');
                return;
            }

            if (res.error) {
                $('#taxCalculationContent').html('<div class="alert alert-danger">' + res.error + '</div>');
                return;
            }

           let deductionHtml = '';
if (res.raw_deductions && Object.keys(res.raw_deductions).length > 0) {
    deductionHtml += '<ul>';
    Object.entries(res.raw_deductions).forEach(([section, amount]) => {
        deductionHtml += `<li><strong>${section}</strong>: ₹${amount}</li>`;
    });
    deductionHtml += '</ul>';
} else {
    deductionHtml = '<p>No deductions declared.</p>';
}
const oldBreakdownTable = buildBreakdownTable(res.old_regime_breakdown || [], res.old_regime_tax);
const newBreakdownTable = buildBreakdownTable(res.new_regime_breakdown || [], res.new_regime_tax);

let html = `
    <div class="form-group">
        <label><strong>Select Tax Regime:</strong></label><br>
        <label class="radio-inline">
            <input type="radio" name="selected_regime" value="Old" ${res.selected_regime === 'Old' ? 'checked' : ''} onchange="toggleTaxRegime()"> Old Regime
        </label>&nbsp;&nbsp;
        <label class="radio-inline">
            <input type="radio" name="selected_regime" value="New" ${res.selected_regime === 'New' ? 'checked' : ''} onchange="toggleTaxRegime()"> New Regime
        </label>
    </div>

    <div id="oldRegimeBox" style="display: ${res.selected_regime === 'Old' ? 'block' : 'none'};">
        <p><strong>Taxable Income:</strong> ₹${res.taxable_income_old}</p>
        <p><strong>Total Deductions:</strong> ₹${res.total_deductions_old}</p>
        <p><strong>HRA Exemption:</strong> ₹${res.hra_exemption}</p>
        <p><strong>Rent paid:</strong> ₹${res.rent_paid}</p>

        <p><strong>Home Loan Interest:</strong> ₹${res.home_loan_interest}</p>
        <p><strong>Breakdown of Deductions:</strong></p>
        ${deductionHtml}
<p><strong>Calculated Tax:</strong> ₹${res.old_regime_tax}</p>
<p><strong>Breakdown of Tax:</strong></p>
${oldBreakdownTable}

    </div>

    <div id="newRegimeBox" style="display: ${res.selected_regime === 'New' ? 'block' : 'none'};">
        <p><strong>Taxable Income:</strong> ₹${res.taxable_income_new}</p>
        <p><strong>Deductions:</strong> ₹0 (not applicable)</p>
<p><strong>Calculated Tax:</strong> ₹${res.new_regime_tax}</p>
<p><strong>Breakdown of Tax:</strong></p>
${newBreakdownTable}

    </div>

`;


            $('#taxCalculationContent').html(html);
            toggleTaxRegime(); // Ensure correct box is shown on modal open
        },

        error: function () {
            $('#taxCalculationContent').html('<div class="alert alert-danger">Error loading tax calculation.</div>');
        }
    });
}
function buildBreakdownTable(breakdown, totalTax) {
    let html = `<table class="table table-bordered">
        <thead><tr>
            <th>Income Slab</th>
            <th>Amount in that Slab</th>
            <th>Tax Rate</th>
            <th>Tax for that Slab</th>
        </tr></thead><tbody>`;

    breakdown.forEach(row => {
        html += `<tr>
            <td>${row.slab}</td>
            <td>₹${Number(row.amount).toLocaleString()}</td>
            <td>${row.rate}%</td>
            <td>₹${Number(row.tax).toLocaleString()}</td>
        </tr>`;
    });

    const cess = Math.round(totalTax * 0.04);
    const grandTotal = totalTax + cess;

    html += `<tr><td colspan="3"><strong>Total Tax</strong></td><td>₹${totalTax.toLocaleString()}</td></tr>`;
    html += `<tr><td colspan="3">+ Cess (4%)</td><td>₹${cess.toLocaleString()}</td></tr>`;
    html += `<tr><td colspan="3"><strong>Grand Total</strong></td><td>₹${grandTotal.toLocaleString()}</td></tr>`;
    html += `</tbody></table>`;

    return html;
}

function saveSelectedRegime() {
    let regime = $('input[name="selected_regime"]:checked').val();
    let employee_id = $('#tax_employee_id').val();
    let declaration_id = $('#tax_declaration_id').val();
    let financial_year = $('#tax_financial_year').val();

    if (!regime) {
        alert("Please select a regime.");
        return;
    }

    $.ajax({
        url: admin_url + 'hr_profile/save_tax_regime',
        method: 'POST',
        data: {
            employee_id: employee_id,
            declaration_id: declaration_id,
            financial_year: financial_year,
            tax_regime: regime
        },
       success: function(response) {
    console.log('Raw response:', response);

    // Try to parse if needed
    try {
        if (typeof response === 'string') {
            response = JSON.parse(response);
        }
    } catch (e) {
        console.warn('Failed to parse JSON:', e);
    }

    // Check success condition
    if (response.success || response === true || response === 'success') {
        alert('Regime saved successfully!');
        $('#taxModal').modal('hide');
    } else {
        alert('Failed to save regime.');
    }
},

    });
}

function toggleTaxRegime() {
    const selected = $('input[name="selected_regime"]:checked').val();
    if (selected === 'Old') {
        $('#oldRegimeBox').show();
        $('#newRegimeBox').hide();
    } else if (selected === 'New') {
        $('#oldRegimeBox').hide();
        $('#newRegimeBox').show();
    }
}



</script>
