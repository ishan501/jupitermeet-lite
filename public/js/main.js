//add headers to all the ajax requests
$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {

  $('.dark-theme-setting').on('click', function () {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    if (currentTheme === 'light') {
      document.documentElement.setAttribute('data-bs-theme', 'dark');
      localStorage.setItem('theme', 'dark');
      $('#moonIcon').hide();
      $('#sunIcon').show();
    } else {
      document.documentElement.setAttribute('data-bs-theme', 'light');
      localStorage.setItem('theme', 'light');
      $('#sunIcon').hide();
      $('#moonIcon').show();
    }
  });


  $(".jm-toggle-password").on("click", function () {
    const passwordInput = $(this)
      .closest(".input-group")
      .find('input[type="password"], input[type="text"]');

    if (passwordInput.attr("type") === "password") {
      passwordInput.attr("type", "text");
      $(this).html(`
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
  <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
  <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
  <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
</svg>
              `);
    } else {
      passwordInput.attr("type", "password");
      $(this).html(`
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
</svg>
              `);
    }
  });

  $("#autoLogin").on("change", function () {
    let email;

    if (this.value == "admin") {
      email = "admin@jupitersoftwares.io";
    } else if (this.value == "user_1") {
      email = "user1@jupitersoftwares.io";
    } else if (this.value == "user_2") {
      email = "user2@jupitersoftwares.io";
    }

    $("#loginButton").attr("disabled", true);

    $("#email").val(email);
    $("#password").val("12345678");
    $("#loginForm").trigger("submit");
  });

  if ($("#toast-simple").is(":visible")) {
    setTimeout(() => {
      $("#toast-simple").fadeOut();
      $("#toast-simple").removeClass("show");
    }, 3000);
  }


  //check if the cookie is accepted
  if (cookieConsent == "enabled" && !localStorage.getItem("cookieAccepted") && !window.location.pathname.includes("meeting")) {
    setTimeout(function () {
      $(".cookie").addClass("show");
    }, 3000);
  }

  //store in the local storage and hide the cookie dialogue
  $(document).on("click", ".confirm-cookie", function () {
    localStorage.setItem("cookieAccepted", true);
    $(".cookie").removeClass("show");
  });

  $("#meetingDashboard").on("submit", function (e) {
    e.preventDefault();

    if (conferenceId.value.length <= 3) {
      showToast(languages.no_meeting);
      return;
    }

    $("#join").attr("disabled", true);

    $.ajax({
      url: "/check-meeting",
      data: htmlEscapeArray($(this).serializeArray()),
      type: "post",
    })
      .done(function (data) {
        data = JSON.parse(data);
        $("#join").attr("disabled", false);

        if (data.success) {
          location.href = "meeting/" + data.id;
        } else {
          showToast(languages.no_meeting);
        }
      })
      .catch(function () {
        showToast(languages.no_meeting);
        $("#join").attr("disabled", false);
      });
  });

  //to prevent XSS vulnerability
  function htmlEscapeArray(input) {
    let data = {};
    $.each(input, function () {
      data[this.name] = this.value
        .replace(/&/g, "")
        .replace(/"/g, "")
        .replace(/'/g, "")
        .replace(/</g, "")
        .replace(/>/g, "");
    });
    return data;
  }

  if (window.location.pathname == '/') {
    $("#monthlyBtn").click(function () {
      $(this).addClass("active");
      $("#yearlyBtn").removeClass("active");

      $(".plan-month").removeClass("d-none");
      $(".plan-year").addClass("d-none");
    });

    $("#yearlyBtn").click(function () {
      $(this).addClass("active");
      $("#monthlyBtn").removeClass("active");

      $(".plan-year").removeClass("d-none");
      $(".plan-month").addClass("d-none");
    });

    $("#monthlyBtn").click();
  }

  // -------------------Loader JS-------------------

  $('.hideLoader').click(function () {
    $("#cover-spin").show();
    setTimeout(function () {
      $("#cover-spin").hide();
    }, 2000);
  });
});

$(window).on("load", function () {
  $("#cover-spin").hide();
});

$(window).on('beforeunload', function () {
  $("#cover-spin").show();
});

// When page is restored from bfcache (back/forward button)
window.addEventListener('pageshow', function (event) {
  if (event.persisted) {
    $("#cover-spin").hide();
  }
});

//handle remove copoun
$("#remove_coupon").on("click", function (e) {
  $('input[name="coupon"]').val('');
  //remove the submit button
  document.querySelector('#form-payment').submit.remove();

  //submit the form
  document.querySelector('#form-payment').submit();
});

//update summary
let updateSummary = (type) => {
  if (type == 'month') {
    document.querySelectorAll('.checkout-month').forEach(function (element) {
      element.classList.add('d-inline-block');
    });
    document.querySelectorAll('.checkout-month').forEach(function (element) {
      element.classList.remove('d-none');
    });
    document.querySelectorAll('.checkout-year').forEach(function (element) {
      element.classList.remove('d-inline-block');
    });
    document.querySelectorAll('.checkout-year').forEach(function (element) {
      element.classList.add('d-none');
    });

    $(".month-label").addClass("active");
    $(".year-label").removeClass("active");

  } else {
    document.querySelectorAll('.checkout-month').forEach(function (element) {
      element.classList.remove('d-inline-block');
    });
    document.querySelectorAll('.checkout-year').forEach(function (element) {
      element.classList.add('d-inline-block');
    });
    document.querySelectorAll('.checkout-year').forEach(function (element) {
      element.classList.remove('d-none');
    });
    document.querySelectorAll('.checkout-month').forEach(function (element) {
      element.classList.add('d-none');
    });

    $(".year-label").addClass("active");
    $(".month-label").removeClass("active");

  }
};

//update billing type
let updateBillingType = (value) => {
  document.querySelectorAll('.checkout-subscription').forEach(function (element) {
    element.classList.remove('d-none');
  });
  document.querySelectorAll('.checkout-subscription').forEach(function (element) {
    element.classList.add('d-block');
  });
}

//payment form
if (document.querySelector('#form-payment')) {
  let url = new URL(window.location.href);

  document.querySelectorAll('[name="interval"]').forEach(function (element) {
    if (element.checked) {
      updateSummary(element.value);
    }

    //listen to interval changes
    element.addEventListener('change', function () {
      url.searchParams.set('interval', element.value);
      history.pushState(null, null, url.href);
      updateSummary(element.value);
    });
  });

  document.querySelectorAll('[name="payment_gateway"]').forEach(function (element) {
    if (element.checked) {
      updateBillingType(element.value);
    }

    //listen to payment gateway changes
    element.addEventListener('change', function () {
      url.searchParams.set('payment', element.value);
      history.pushState(null, null, url.href);
      updateBillingType(element.value);
    });
  });

  //if the Add a coupon button is clicked
  document.querySelector('#coupon') && document.querySelector('#coupon').addEventListener('click', function (e) {
    e.preventDefault();

    this.classList.add('d-none');
    document.querySelector('#coupon-input').classList.remove('d-none');
    document.querySelector('input[name="coupon"]').removeAttribute('disabled');
  });

  //if the Cancel coupon button is clicked
  document.querySelector('#coupon-cancel') && document.querySelector('#coupon-cancel').addEventListener('click', function (e) {
    e.preventDefault();

    document.querySelector('#coupon').classList.remove('d-none');
    document.querySelector('#coupon-input').classList.add('d-none');
    document.querySelector('input[name="coupon"]').setAttribute('disabled', 'disabled');
  });

  //if the country value changes
  document.querySelector('#i-country').addEventListener('change', function () {
    document.querySelector('#form-payment').submit.remove();
    document.querySelector('#form-payment').submit();
  });
}

//handle plan month click
document.querySelector('#plan-month') && document.querySelector('#plan-month').addEventListener("click", function () {
  document.querySelectorAll('.plan-month').forEach(element => element.classList.add('d-block'));
  document.querySelectorAll('.plan-year').forEach(element => element.classList.remove('d-block'));
});

//handle plan year click
document.querySelector('#plan-year') && document.querySelector('#plan-year').addEventListener("click", function () {
  document.querySelectorAll('.plan-year').forEach(element => element.classList.add('d-block'));
  document.querySelectorAll('.plan-month').forEach(element => element.classList.remove('d-block', 'plan-preload'));
});

//apply theme to the Google reCAPTCHA
if (document.querySelector('.g-recaptcha')) {
  document.querySelector('.g-recaptcha').setAttribute("data-theme", document.documentElement.getAttribute(
    'data-bs-theme'));
}

function showToast(message, type = "success") {
  const toast = $("#toast-simple");
  const toastBody = toast.find(".toast-body");

  toastBody.text(message || "An error occurred, please try again!");

  toast.removeClass("bg-success").addClass("show");

  toast.fadeIn();

  setTimeout(() => {
    toast.fadeOut();
    toast.removeClass("show");
  }, 3000);
}

//set href into the social links
var fullUrl = location.protocol + '//' + location.hostname + location.pathname; // Full current URL
$('#fbShare').attr('href', 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(fullUrl) + '&quote=' + encodeURIComponent(socialInvitation));
// $('#fbShare').attr('href', 'https://www.facebook.com/sharer/sharer.php?u=' + location.hostname + '&quote=' + socialInvitation);
$('#twitterShare').attr('href', 'https://twitter.com/share?url=' + location.hostname + '&text=' + socialInvitation);
$('#waShare').attr('href', 'https://api.whatsapp.com/send?text=' + socialInvitation + ' \n ' + location.hostname);
