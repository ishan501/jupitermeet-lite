$("document").ready(function () {
  const pathname = window.location.pathname;

  $('.hideLoader').on('click', function (e) {
    setTimeout(function () {
      $('#cover-spin').hide();
    }, 2000);
  });

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

  if ($("#toast-simple").is(":visible")) {
    setTimeout(() => {
      $("#toast-simple").fadeOut();
      $("#toast-simple").removeClass("show");
    }, 3000);
  }

  // --------------Meeting Module---------------
  if (pathname == "/admin/meetings") {
    //update meeting status via toggle
    $(document).on("change", ".toggle-meeting-status", function () {
      const toggleStatus = $(this);
      toggleStatus.prop("disabled", true);

      const meetingStatus = $(this).prop("checked") ? "active" : "inactive";
      const meetingId = $(this).data("id");

      $.ajax({
        type: "POST",
        url: "/admin/meeting/update-status",
        data: {
          _token: $('meta[name="csrf-token"]').attr("content"),
          meetingStatus,
          meetingId,
        },
        success: function (response) {
          showToast(response.message);
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () {
          toggleStatus.prop("disabled", false);
        },
      });
    });
  }

  // --------------User Module---------------
  if (pathname == "/admin/users") {
    //assign plan to user through dropdown
    $(".assignPlanDropdown").on("change", function (e) {
      e.preventDefault();
      let value = $(this).val().split("|");
      $.ajax({
        type: "POST",
        url: "/admin/user/assign-plan",
        data: {
          _token: $('meta[name="csrf-token"]').attr("content"),
          plan_id: value[0],
          user_id: value[1],
        },
        success: function (response) {
          showToast(response.message);
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () {
          toggleStatus.prop("disabled", false);
        },
      });
    });

    //update user status via dropdown
    $(document).on("change", ".toggle-user-status", function () {
      const toggleStatus = $(this);
      toggleStatus.prop("disabled", true);

      const userStatus = $(this).prop("checked") ? "active" : "inactive";
      const userId = $(this).data("id");

      $.ajax({
        type: "POST",
        url: "/admin/user/update-status",
        data: {
          _token: $('meta[name="csrf-token"]').attr("content"),
          userStatus,
          userId,
        },
        success: function (response) {
          showToast(response.message);
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () {
          toggleStatus.prop("disabled", false);
        },
      });
    });
  }

  if (pathname == "/admin/users/create") {

    // Toggle password
    $('.toggle-password').on('click', function () {
      const $input = $('#passwordField');
      const $icon = $(this).find('i');

      if ($input.attr('type') === 'password') {
        $input.attr('type', 'text');
        $icon.removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
      } else {
        $input.attr('type', 'password');
        $icon.removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
      }
    });

    // Generate random password
    $('.generate-password').on('click', function () {
      const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+';
      let password = '';
      for (let i = 0; i < 12; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      $('#passwordField').val(password);
    });
  }

  // ------------------Page Module-----------------
  if (
    pathname == "/admin/pages/create" ||
    pathname.startsWith("/admin/pages/edit")
  ) {
    const initQuill = (el) => {
      const quill = new Quill("#pageContentEditor", {
        modules: {
          toolbar: [
            ["bold", "italic", "underline", "link"],
            [{ list: "ordered" }, { list: "bullet" }],
            [{ align: [] }]
          ],
        },
        placeholder: "Type your text here...",
        theme: "snow",
      });

      const toolbar = el.querySelector(".ql-toolbar");

      if (toolbar) {
        const classes = [
          "px-5",
          "border-top-0",
          "border-start-0",
          "border-end-0",
        ];
        toolbar.classList.add(...classes);
      }

      quill.on("text-change", function (delta, oldDelta, source) {
        content.value = quill.root.innerHTML;
      });
    };
    if (pageForm) {
      initQuill(pageForm);
    }
  }

  // ------------------Plugin Module-----------------
  if (
    pathname == "/admin/plugins"
  ) {
    $(document).on('click', '.copy-token', function () {
      let btn = $(this);
      let token = btn.data('token');

      navigator.clipboard.writeText(token);

      btn.html(`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
              <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
            </svg>`);
      setTimeout(function () {
        btn.html(`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"/>
</svg>`);
      }, 1500);
    });

    $(document).on("change", ".toggle-plugin-status", function () {
      const toggleStatus = $(this);
      toggleStatus.prop("disabled", true);

      const pluginStatus = $(this).prop("checked") ? 1 : 2;
      const pluginId = $(this).data("id");

      $.ajax({
        type: "POST",
        url: "/admin/plugin/update-status",
        data: {
          _token: $('meta[name="csrf-token"]').attr("content"),
          pluginStatus,
          pluginId,
        },
        success: function (response) {
          showToast(response.message);
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () {
          toggleStatus.prop("disabled", false);
        },
      });
    });

  }

  if (
    pathname == "/admin/plugins/create"
  ) {
    $(document).on('click', '.copy-token', function () {
      const btn = $(this);
      const token = btn.closest('.input-group').find('input').val();

      navigator.clipboard.writeText(token).then(() => {
        const icon = btn.find('i');
        showToast(translations.copied_to_clipboard);

        icon.removeClass('bi-copy').addClass('bi-check2');

        setTimeout(() => {
          icon.removeClass('bi-check2').addClass('bi-copy');
        }, 1500);
      });
    });
  }

  // ------------------Addons Module-----------------
  if (
    pathname == "/admin/addons"
  ) {
    $(document).on("change", ".toggle-addon-status", function () {
      const toggleStatus = $(this);
      toggleStatus.prop("disabled", true);

      const addonStatus = $(this).prop("checked") ? 'active' : 'inactive';
      const addonId = $(this).data("id");

      $.ajax({
        type: "POST",
        url: "/admin/addons/update-status",
        data: {
          _token: $('meta[name="csrf-token"]').attr("content"),
          addonStatus,
          addonId,
        },
        success: function (response) {
          showToast(response.message);
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () {
          toggleStatus.prop("disabled", false);
        },
      });
    });

  }

  // ------------------Plan Module-----------------
  if (pathname == "/admin/plans") {
    //update plan status via toggle
    $(document).on("change", ".toggle-plan-status", function () {
      const toggleStatus = $(this);
      toggleStatus.prop("disabled", true);

      const planStatus = $(this).prop("checked") ? 1 : 2;
      const planId = $(this).data("id");

      $.ajax({
        type: "POST",
        url: "/admin/plan/update-status",
        data: {
          _token: $('meta[name="csrf-token"]').attr("content"),
          planStatus,
          planId,
        },
        success: function (response) {
          showToast(response.message);
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () {
          toggleStatus.prop("disabled", false);
        },
      });
    });

    //update plan popularity via toggle
    $(document).on("change", ".toggle-plan-popularity", function () {
      const toggleStatus = $(this);
      toggleStatus.prop("disabled", true);

      const planStatus = $(this).prop("checked") ? 1 : 2;
      const planId = $(this).data("id");

      $.ajax({
        type: "POST",
        url: "/admin/plan/update-popularity",
        data: {
          _token: $('meta[name="csrf-token"]').attr("content"),
          planStatus,
          planId,
        },
        success: function (response) {
          if (planStatus === 1) {
            $(".toggle-plan-popularity").prop("checked", false);
            toggleStatus.prop("checked", true);
          }
          showToast(response.message);
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () {
          toggleStatus.prop("disabled", false);
        },
      });
    });
  }

  // ------------------Coupon Module-----------------
  if (
    pathname == "/admin/coupons" ||
    pathname == "/admin/coupons/create" ||
    pathname.startsWith("/admin/coupons/edit")
  ) {
    //update plan status via toggle
    $(document).on("change", ".toggle-coupon-status", function () {
      const toggleStatus = $(this);
      toggleStatus.prop("disabled", true);

      const couponStatus = $(this).prop("checked") ? 1 : 2;
      const couponId = $(this).data("id");

      $.ajax({
        type: "POST",
        url: "/admin/coupon/update-status",
        data: {
          _token: $('meta[name="csrf-token"]').attr("content"),
          couponStatus,
          couponId,
        },
        success: function (response) {
          showToast(response.message);
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () {
          toggleStatus.prop("disabled", false);
        },
      });
    });

    //copy coupon from create and edit form
    $("#couponCodeCopy").on("click", function (e) {
      e.preventDefault();
      let link = $("#couponCode").val();
      var inp = document.createElement("input");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });

    //toggle input type (days and percentage Off)
    if (document.querySelector("#form-coupon")) {
      document.querySelector("#i-type").addEventListener("change", function () {
        if (document.querySelector("#i-type").value == 1) {
          document
            .querySelector("#form-group-redeemable")
            .classList.remove("d-none");
          document
            .querySelector("#form-group-discount")
            .classList.add("d-none");
          document
            .querySelector("#i-percentage")
            .setAttribute("disabled", "disabled");
        } else {
          document
            .querySelector("#form-group-redeemable")
            .classList.add("d-none");
          document
            .querySelector("#form-group-discount")
            .classList.remove("d-none");
          document.querySelector("#i-percentage").removeAttribute("disabled");
        }
      });
    }
  }
  // --------------Taxrate Module-----------------------
  if (pathname == "/admin/taxrates") {
    //update plan status via toggle
    $(document).on("change", ".toggle-taxrate-status", function () {
      const toggleStatus = $(this);
      toggleStatus.prop("disabled", true);

      const taxrateStatus = $(this).prop("checked") ? 1 : 2;
      const taxrateId = $(this).data("id");

      $.ajax({
        type: "POST",
        url: "/admin/taxrate/update-status",
        data: {
          _token: $('meta[name="csrf-token"]').attr("content"),
          taxrateStatus,
          taxrateId,
        },
        success: function (response) {
          showToast(response.message);
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () {
          toggleStatus.prop("disabled", false);
        },
      });
    });
  }

  // --------------Payment Gateway (Stripe)-----------------------
  if (pathname == "/admin/payment-gateways/stripe") {
    // Copy stripe webhook URL
    $("#webhookUrlStripeCopy").on("click", function (e) {
      e.preventDefault();
      let link = $("#i-stripe-wh-url").val();
      var inp = document.createElement("input");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });
  }

  // --------------Payment Gateway (Paypal)-----------------------
  if (pathname == "/admin/payment-gateways/paypal") {
    // Copy paypal webhook URL
    $("#webhookUrlPaypalCopy").on("click", function (e) {
      e.preventDefault();
      let link = $("#i-paypal-wh-url").val();
      var inp = document.createElement("input");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });
  }

  // --------------Payment Gateway (Paystack)-----------------------
  if (pathname == "/admin/payment-gateways/paystack") {
    // Copy paystack webhook URL
    $("#webhookUrlPaystackCopy").on("click", function (e) {
      e.preventDefault();
      let link = $("#i-paystack-wh-url").val();
      var inp = document.createElement("input");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });

    // Copy paystack callback URL
    $("#callbackUrlPaystackCopy").on("click", function (e) {
      e.preventDefault();
      let link = $("#i-paystack-cb-url").val();
      var inp = document.createElement("input");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });
  }

  // --------------Payment Gateway (Razorpay)-----------------------
  if (pathname == "/admin/payment-gateways/razorpay") {
    // Copy razorpay webhook URL
    $("#webhookUrlrazorpayCopy").on("click", function (e) {
      e.preventDefault();
      let link = $("#i-razorpay-wh-url").val();
      var inp = document.createElement("input");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });
  }

  // ---------------Email Template_-------------------------

  if (pathname.startsWith("/admin/email-templates/edit")) {
    const initQuill = (el) => {
      const quill = new Quill("#emailTemplateContentEditor", {
        modules: {
          toolbar: [
            ["bold", "italic", "underline", "link"],
            [{ list: "ordered" }, { list: "bullet" }],
          ],
        },
        placeholder: "Type your text here...",
        theme: "snow",
      });

      const toolbar = el.querySelector(".ql-toolbar");

      if (toolbar) {
        const classes = [
          "px-5",
          "border-top-0",
          "border-start-0",
          "border-end-0",
        ];
        toolbar.classList.add(...classes);
      }

      quill.on("text-change", function (delta, oldDelta, source) {
        content.value = quill.root.innerHTML;
      });
    };
    if (emailTemplateForm) {
      initQuill(emailTemplateForm);
    }

    $(document).on('click', '.copy-variable', function (e) {
      const btn = e.target.closest('.copy-variable');
      if (!btn) return;

      const variable = btn.getAttribute('data-variable');

      navigator.clipboard.writeText(variable).then(() => {
        showToast(translations.copied_to_clipboard);
      });
    });
  }

  if (pathname == "/admin/signaling-server") {
    $("#checkSignaling").on("click", function () {
      $.ajax({
        url: "/admin/check-signaling-server",
        success: function (response) {
          $("#checkSignaling").attr("disabled", true);
          $(".status-text").text(response.status);

          if (response.status == "Running") {
            $(".status-indicator").removeClass("status-red").addClass("status-green");
            $(".status-text").removeClass("text-red").addClass("text-green");
          } else {
            $(".status-indicator").removeClass("status-green").addClass("status-red ");
            $(".status-text").removeClass("text-green").addClass("text-red ");
          }
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () {
          $("#checkSignaling").attr("disabled", false);
        },
      });
    });

    $("#checkSignaling").trigger("click");

  }

  //  -------------------Manage Update-------------------
  if (pathname == "/admin/manage-updates") {
    $("#checkForUpdate").on("click", function () {
      $(this).attr("disabled", true);
      $.ajax({
        url: "/admin/check-for-update",
        success: function (response) {
          data = JSON.parse(response);
          if (data.success) {
            $("#downloadUpdate").removeAttr("hidden");
            let changelog = "";
            $.each(data.changelog, function (key, value) {
              changelog +=
                "<b>V " + key + ": </b>" + "<br>" + value + "<br><br>";
            });
            $("#changelog").html(changelog || "-");
            showToast(translations.update_available + data.version);
          } else if (data.error) {
            showToast(data.error);
            $("#checkForUpdate").attr("disabled", false);
          } else {
            $("#checkForUpdate").attr("disabled", false);
            showToast(translations.already_latest_version + data.version);
          }
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () { },
      });
    });

    $("#downloadUpdate").on("click", function () {
      $(this).attr("disabled", true);
      $.ajax({
        url: "/admin/download-update",
        success: function (response) {
          data = JSON.parse(response);
          $("#downloadUpdate").removeAttr("hidden");

          if (data.success) {
            showToast(translations.application_updated);
          } else if (data.error) {
            showToast(data.error);
          } else {
            $("#downloadUpdate").attr("disabled", false);
            showToast(translations.update_failed + data.error);
          }
        },
        error: function (xhr, status, error) {
          showToast("Something went wrong, please try again!");
        },
        complete: function () { },
      });
    });
  }


  if (pathname == "/admin/settings/smtp") {
    //call an api and test SMTP
    $("#testSmtp").on("submit", function (e) {
      e.preventDefault();
      $("#testSmtpButton").attr("disabled", true);

      $.ajax({
        url: "/admin/setting/test-smtp",
        type: "post",
        data: {
          _token: $('meta[name="csrf-token"]').attr("content"),
          email: smtpEmail.value,
        },
      })
        .done(function (data) {
          data = JSON.parse(data);
          $("#testSmtpButton").attr("disabled", false);

          if (data.success) {
            $("#error").addClass("d-none");
            $("#success").removeClass("d-none");
          } else {
            $("#success").addClass("d-none");
            $(".log").text(data.error);
            $("#error").removeClass("d-none");
          }
        })
        .catch(function () {
          showToast();
        });
    });
  }

  if (pathname == "/admin/settings/api-token") {
    //copy api token to the clipboard
    $("#copyApiToken").on("click", function () {
      let inp = document.createElement("textarea");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = api_token.value;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });
  }

  if (pathname == "/admin/settings/social-login") {
    //copy google callback url
    $("#google_cb_url_copy").on("click", function (e) {
      e.preventDefault();
      let link = $("#i-google-cb-url").val();
      var inp = document.createElement("input");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });

    //copy twitter callback url
    $("#twitter_cb_url_copy").on("click", function (e) {
      e.preventDefault();
      let link = $("#i-twitter-cb-url").val();
      var inp = document.createElement("input");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });

    //copy linked in callback url
    $("#linkedin_cb_url_copy").on("click", function (e) {
      e.preventDefault();
      let link = $("#i-linkedin-cb-url").val();
      var inp = document.createElement("input");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });

    //copy facebook callback url
    $("#facebook_cb_url_copy").on("click", function (e) {
      e.preventDefault();
      let link = $("#i-facebook-cb-url").val();
      var inp = document.createElement("input");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showToast(translations.copied_to_clipboard);
    });
  }

  if (pathname == "/admin/dashboard") {
    window.ApexCharts && (new ApexCharts(document.getElementById('usersPaidFreePieChart'), {
      chart: {
        type: "donut",
        fontFamily: 'inherit',
        height: 240,
        sparkline: {
          enabled: true
        },
        animations: {
          enabled: true
        },
      },
      fill: {
        opacity: 1,
      },
      series: [paidUsers, freeUsers],
      labels: [translations.paid_pie_chart, translations.free_pie_chart],
      tooltip: {
        theme: 'dark'
      },
      grid: {
        strokeDashArray: 4,
      },
      colors: ["#06ABD7", "#9BDDEF"],
      legend: {
        show: true,
        position: 'bottom',
        offsetY: 12,
        markers: {
          width: 10,
          height: 10,
          radius: 100,
        },
        itemMargin: {
          horizontal: 8,
          vertical: 8
        },
      },
      tooltip: {
        fillSeriesColor: false
      },
      options: {
        borderColor: 'none',
      }
    })).render();

    const monthNames = {
      "1": translations.jan,
      "2": translations.feb,
      "3": translations.mar,
      "4": translations.apr,
      "5": translations.may,
      "6": translations.jun,
      "7": translations.jul,
      "8": translations.aug,
      "9": translations.sep,
      "10": translations.oct,
      "11": translations.nov,
      "12": translations.dec
    };

    const categories = Object.values(monthNames);

    function renderBarGraph(elementId, graphData, seriesName) {
      let data = Array(12).fill(0);

      Object.keys(graphData).forEach(month => {
        let index = parseInt(month) - 1;
        data[index] = graphData[month];
      });

      const options = {
        chart: {
          type: "bar",
          fontFamily: 'inherit',
          height: 240,
          parentHeightOffset: 0,
          toolbar: {
            show: false
          },
          animations: {
            enabled: true
          }
        },
        plotOptions: {
          bar: {
            columnWidth: '50%'
          }
        },
        dataLabels: {
          enabled: false
        },
        fill: {
          opacity: 1
        },
        series: [{
          name: seriesName,
          data: data
        }],
        tooltip: {
          theme: 'dark'
        },
        grid: {
          padding: {
            top: -20,
            right: 0,
            left: -4,
            bottom: -4
          },
          strokeDashArray: 4
        },
        xaxis: {
          categories: categories,
          labels: {
            padding: 4
          },
          tooltip: {
            enabled: false
          },
          axisBorder: {
            show: false
          }
        },
        yaxis: {
          labels: {
            padding: 4,
            formatter: value => value.toFixed(0)
          }
        },
        colors: ['#06ABD7'],
        legend: {
          show: false
        }
      };

      window.ApexCharts && (new ApexCharts(document.getElementById(elementId), options)).render();
    }

    renderBarGraph('incomeBarGraph', monthlyIncome, translations.monthly_income);
    renderBarGraph('userRegistrationBarGraph', monthlyUserRegistered, translations.monthly_user_registered);
    renderBarGraph('meetingsBarGraph', monthlyMeetings, translations.monthly_meetings);
  }
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
