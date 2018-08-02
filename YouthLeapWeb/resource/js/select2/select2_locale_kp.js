/**
 * Select2 Korean translation.
 */
(function ($) {
    "use strict";

    $.fn.select2.locales['kp'] = {
        formatNoMatches: function () { return "검색자료가 없습니다."; },
        formatInputTooShort: function (input, min) { var n = min - input.length; return n + "문자 더 입력하여주십시오."; },
        formatInputTooLong: function (input, max) { var n = input.length - max; return "검색문자렬이 " + n + "문자 더 많습니다."; },
        formatSelectionTooBig: function (limit) { return "최대로 " + limit + "개밖에 선택할수 없습니다."; },
        formatLoadMore: function (pageNumber) { return "읽기중･･･"; },
        formatSearching: function () { return "검색중･･･"; }
    };

    $.extend($.fn.select2.defaults, $.fn.select2.locales['kp']);
})(jQuery);
