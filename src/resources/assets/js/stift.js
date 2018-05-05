/**
 * Stift js library
 *
 * @since  2018-05-05
 * @author Attila Fulop
 */

function duration_secs_to_human_readable(seconds, withSeconds = false) {
    var seconds = parseInt(seconds, 10);

    var days = Math.floor(seconds / (3600*6));
    seconds  -= days*3600*6;

    var hrs   = Math.floor(seconds / 3600);
    seconds  -= hrs*3600;

    var mnts = Math.floor(seconds / 60);
    seconds  -= mnts*60;

    result = '';

    if (days > 0) {
        result += result.length ? ' ' : '';
        result += days + "d"
    }

    if (hrs > 0) {
        result += result.length ? ' ' : '';
        result += hrs + "h"
    }

    if (mnts > 0) {
        result += result.length ? ' ' : '';
        result += mnts + "m"
    }

    if (seconds > 0) {
        result += result.length ? ' ' : '';
        result += seconds + "s"
    } else if (result.length == 0) {
        result = '0s';
    }

    return result;
}