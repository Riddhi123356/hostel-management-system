/* =====================================================================
   Hostel Management System — Shared JavaScript helpers
   ===================================================================== */

/** Toggle a password field between hidden/visible (used on sign-in pages). */
function togglePassword(checkbox, fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    field.type = checkbox.checked ? "text" : "password";
}

/** Validate the "change password" form before submit. */
function validatePassword() {
    const newPass = document.getElementById("newPass");
    const confirmPass = document.getElementById("confirmPass");
    const errorBox = document.getElementById("passwordError");

    if (!newPass || !confirmPass) return true;

    if (newPass.value !== confirmPass.value) {
        if (errorBox) {
            errorBox.textContent = "New password and confirm password do not match.";
            errorBox.style.display = "block";
        } else {
            alert("New password and confirm password do not match.");
        }
        return false;
    }

    if (newPass.value.length < 6) {
        if (errorBox) {
            errorBox.textContent = "New password must be at least 6 characters long.";
            errorBox.style.display = "block";
        } else {
            alert("New password must be at least 6 characters long.");
        }
        return false;
    }

    return true;
}

/** Show/hide gate pass vs leave specific fields on the gate pass form. */
function showFields() {
    const typeSelect = document.getElementById("typeSelect");
    if (!typeSelect) return;
    const type = typeSelect.value;

    const date1 = document.getElementById("date1");
    const date2 = document.getElementById("date2");
    const attachmentField = document.getElementById("attachmentField");

    [date1, date2, attachmentField].forEach(el => { if (el) el.style.display = "none"; });

    if (type === "Gate Pass") {
        if (date1) date1.style.display = "block";
        if (attachmentField) attachmentField.style.display = "block";
    }

    if (type === "Leave") {
        if (date1) date1.style.display = "block";
        if (date2) date2.style.display = "block";
        if (attachmentField) attachmentField.style.display = "block";
    }
}

/** Generic confirm-before-submit helper, used on "Solve" / approve buttons. */
function confirmAction(message) {
    return confirm(message || "Are you sure?");
}

/** Auto-calculate percentage on the promotion form as marks are typed. */
function calculatePercentage() {
    const total = document.getElementById("total_marks");
    const obtained = document.getElementById("obtained_marks");
    const percentage = document.getElementById("percentage");

    if (!total || !obtained || !percentage) return;

    const t = parseFloat(total.value);
    const o = parseFloat(obtained.value);

    if (t > 0 && o >= 0) {
        percentage.value = ((o / t) * 100).toFixed(2);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const totalEl = document.getElementById("total_marks");
    const obtainedEl = document.getElementById("obtained_marks");
    if (totalEl && obtainedEl) {
        totalEl.addEventListener("input", calculatePercentage);
        obtainedEl.addEventListener("input", calculatePercentage);
    }
});
