<script>
import Apis from "../apis";
import Helpers from "../helpers";
import { Modal } from 'bootstrap';

export default {
    name: "ShopIndex",
    data() {
        return {
            isLoading: false,
            products: [],
            payment_methods: [],
            payment_selected: '',
            product_selected: '',
        }
    },
    methods: {
        fetchProduct() {
            Apis.product.index({}).then(res => {
                if (res.status === 200) this.products = res.data.data;
            })
        },
        fetchPaymentMethod() {
            Apis.payment_method.index({}).then(res => {
                if (res.status === 200) this.payment_methods = res.data.data;
            })
        },
        selectPayment(productId) {
            this.product_selected = productId;
            const modal = new Modal(this.$refs.paymentModal);
            modal.show();
        },
        buyProduct() {
            if (!this.payment_selected) return;
            this.isLoading = true;
            Apis.product.buy({
                product_id: this.product_selected,
                payment_code: this.payment_selected
            }).then(res => {
                if (res.status === 201) return window.location.href = '/shops/invoices'
            }).catch(err => {
                if (err.response.status === 401) return window.location.href = '/login'
                throw err;
            }).finally(() => this.isLoading = false)
        }
    },
    computed: {
        helpers() {
            return Helpers;
        }
    },
    mounted() {
        this.fetchProduct();
        this.fetchPaymentMethod();
    }
}
</script>

<template>
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-lg-3" v-for="(datum) in products">
        <div class="card mb-4">
          <img class="card-img-top" src="/images/no-image.png" alt="Card image cap">
          <div class="card-body text-center">
            <h5 class="card-title">{{ datum.name }}</h5>
            <div class="card-text text-secondary mb-2">{{ datum.sku }}</div>
            <h6 class="mb-4"><b>{{ helpers.rupiah(datum.price) }}</b></h6>
            <button class="btn btn-success w-100" @click="selectPayment(datum.id)">Beli</button>
          </div>
        </div>
      </div>
    </div>
      <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true" ref="paymentModal">
          <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                  <div class="modal-header">
                      <h6 class="modal-title" id="paymentModalLabel">Metode Pembayaran</h6>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="mb-3">
                          <label class="form-label">Pilih metode pembayaran berikut:</label>
                          <select class="form-select" v-model="payment_selected">
                              <option :value="pay_sel.code" v-for="(pay_sel) in payment_methods">{{pay_sel.name}}</option>
                          </select>
                      </div>
                      <button class="btn btn-success w-100" type="button" disabled v-if="isLoading">
                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                          <span class="sr-only">Loading...</span>
                      </button>
                      <button class="btn btn-success w-100" @click="buyProduct" v-else>Checkcout</button>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<style scoped>

</style>
