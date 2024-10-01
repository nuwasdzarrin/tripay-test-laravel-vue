const helper = {
    rupiah: (number) => {
        if (!number) return 0;
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            maximumFractionDigits: 0,
        }).format(number);
    }
}

export default helper;
