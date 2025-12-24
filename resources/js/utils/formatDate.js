import dayjs from "dayjs";
import "dayjs/locale/lo";

dayjs.locale("lo");

export function formatDate(date, type = "slash") {
  if (!date) return "-";

  switch (type) {

    // 17/01/2025
    case "slash":
      return dayjs(date).format("DD/MM/YYYY");

    // 17-01-2025
    case "dash":
      return dayjs(date).format("DD-MM-YYYY");

    // 17 ມັງກອນ 2025
    case "lao":
      return dayjs(date).format("DD MMMM YYYY");

    // YYYY-MM-DD
    case "ymd":
      return dayjs(date).format("YYYY-MM-DD");

    // ເວລາເທົ່ານັ້ນ 14:35:20
    case "time":
      return dayjs(date).format("HH:mm:ss");

    // ວັນ + ເວລາ 17/01/2025 14:35:20
    case "datetime":
      return dayjs(date).format("DD/MM/YYYY HH:mm:ss");

    // ວັນ + ເວລາ ແບບລາວ 17 ມັງກອນ 2025 14:35
    case "datetime_lao":
      return dayjs(date).format("DD MMMM YYYY HH:mm");

    default:
      return dayjs(date).format("DD/MM/YYYY");
  }
}
