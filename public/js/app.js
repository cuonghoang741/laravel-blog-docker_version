const BASE_API = "/web-api/v1";
const BASE_API_AUTH = "/web-api/auth/v1";
const TIME_STRING = {
  date_time: "MM/DD/YYYY hh:mm A"
}


$(function () {
  dropdownSelect();
})
function dropdownSelect(className = ".dropdown-select",
                        callback = () => {
                        },
                        condition = function () {
                          return true;
                        }
) {
  //chỉ cần thêm class dropdown-select vào dropdown
  $.each($(`${className} .dropdown-item, ${className} .dropdown-option`), function () {
    $(this).off('click');
    $(this).on("click", function (e) {
      const self = e.currentTarget;
      const text = $(e.currentTarget).html();
      const value = $(e.currentTarget).data("value");
      const print = $(this).closest(className).find(".print").length
        ? $(this).closest(className).find(".print")
        : $(this).closest(className).find(".dropdown-toggle");
      const valid = condition(e.currentTarget);
      if (!valid) {
        return;
      }
      if ($(self).hasClass('multiple') && value) {
        let old_value = $(print).eq(0).data('value');
        old_value += "";
        if (!$(print).eq(0).data('value')) {
          $(print).eq(0).html('');
          $(self).closest('.dropdown-menu').find('[data-value=0]').eq(0).removeClass('active');
        }
        if ($(self).hasClass('active')) {
          old_value = old_value.split(',');
          const filter_old_value = old_value.filter((word) => word != value);
          $(print).eq(0).data("value", (filter_old_value.join(',')));
          $(print).eq(0).find(`[data-value=${$(self).data('value')}]`).remove();
          $(this).removeClass("active");
        } else {
          $(print).eq(0).append(text);
          if (old_value) {
            $(print).eq(0).data("value", old_value + "," + value);
          } else {
            $(print).eq(0).data("value", value);
          }
          $(this).addClass("active");
        }
      } else {
        $(print).html(text);
        $(print).data("value", value);
        $(print).attr("data-value", value);
        console.log(value)
        $(this)
          .closest(className)
          .find(".dropdown-item")
          .removeClass("active");
        $(this)
          .closest(className)
          .find(".dropdown-option")
          .removeClass("active");
        $(this).addClass("active");
      }
      const is_null = !$(print).text().length;
      if (is_null) {
        $(print).html('<span class="is-null">Chưa có mục được chọn</span>')
      } else {
        $(print).find('.is-null').remove();
      }
      callback(e.currentTarget);
    });
  });

  if ($(`${className} .dropdown-item.active, ${className} .dropdown-option.active`).length) {
    const btn_active = $(`${className} .dropdown-item.active, ${className} .dropdown-option.active`);
    $.each(btn_active, function () {
      $(this).trigger('click')
    })
    $.each(btn_active, function () {
      $(this).trigger('click')
    })
  }
}

function quickToastMixin(option="success", title, config={link_href: "", link_text: ""},timerProgressBar=false) {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar,
    html: config.link_href ? `<a class="text-decoration-underline-hover text-black fs-7 fw-bold opacity-5" onclick="userSaved.show('jobs')" href="${config.link_href}">${config.link_text}</a>` : "",
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });
  Toast.fire({
    icon: option,
    title: title,
  });
}

function showSoon() {
  quickToastMixin("info","Feature under development")
}

async function onSelectCity(city) {
  await fillId(city);
  getLocations(city);
}


async function fillId(city) {
  if (!city.trip_advisor_id) {
    return await axios.get(BASE_API + `/ai/trip-plan/cities/${city.id}/fill-id`)
  }
}

function getLocations(city) {
  axios.get(BASE_API + `/ai/trip-plan/cities/${city.id}/locations`);
}
