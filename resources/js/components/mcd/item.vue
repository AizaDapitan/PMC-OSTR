<template>
  <div v-if="errors_exist" class="alert alert-danger" role="alert">
    Whoops! Something didn't work.
    <ul>
      <div v-for="error in errors" :key="error.id">
        <li>{{ error[0] }}</li>
      </div>
    </ul>
  </div>
  <hr />
  <form role="form" method="GET" action="">
    <div class="modal-body">
      <div class="row">
        <h4>Order Details</h4>
        <div class="col-lg-12">
          <div class="form-group row">
            <label for="posted-date" class="">
              Posted Date : {{ this.form.postedDate }}<br />
              Status : {{ this.form.status }} <br />
              Remarks : {{ this.form.remarks }}
            </label>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label for="item"> Item </label>
            <input
              class="form-control input-sm"
              v-model="form.item_code"
              disabled="true"
            />
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label for="ordered-qty"> Ordered Qty </label>
            <input
              class="form-control input-sm"
              v-model="form.ordered_qty"
              disabled="true"
            />
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label for="issued-qty"> Issued Qty </label>
            <input
              class="form-control input-sm"
              v-model="form.issued_qty"
              disabled="true"
            />
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label for="balance"> Balance </label>
            <input
              class="form-control input-sm"
              v-model="form.balance"
              disabled="true"
            />
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label for="issuance-qty">
              Issuance Qty.
              <span class="text-danger" aria-required="true"> * </span>
            </label>
            <input
              type="number"
              class="form-control input-sm"
              v-model="form.issuance_qty"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button
        type="reset"
        class="btn btn-white tx-13 btn-uppercase"
        @click.prevent="closeDialog"
      >
        <i data-feather="x-circle" class="mg-r-5"></i> Cancel
      </button>
      <button
        type="submit"
        class="btn btn-primary tx-13 btn-uppercase"
        @click.prevent="saveItem"
        :disabled="this.isCompleted"
      >
        <i data-feather="save" class="mg-r-5"></i> Save
      </button>
    </div>
  </form>
  <toast
    :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
  ></toast>
</template>
<script>
import { FilterService, FilterMatchMode } from "primevue/api";
export default {
  inject: ["dialogRef"],
  data() {
    return {
      errors_exist: false,
      errors: {},
      products: this.dialogRef.data.products,
      filteredProducts: null,
      selectedProduct: this.dialogRef.data.description,
      selected: false,
      isCompleted:false,
      form: {
        item_id: this.dialogRef.data.item_id,
        postedDate: this.dialogRef.data.postedDate,
        status: "Partially Issued",
        remarks: this.dialogRef.data.remarks,
        item_code: this.dialogRef.data.item_code,
        ordered_qty: this.dialogRef.data.ordered_qty,
        issued_qty: this.dialogRef.data.issued_qty,
        balance:
          this.dialogRef.data.ordered_qty - this.dialogRef.data.issued_qty,
        received_by: this.dialogRef.data.received_by,
        issuance_qty: 0,
      },
    };
  },
  created() {
    if (this.form.balance == 0) {
      this.form.status = "Fully Issued";
      this.isCompleted = true;
    }
  },
  methods: {
    closeDialog() {
      this.dialogRef.close();
    },
    async saveItem() {
      this.errors = {};
      this.errors_exist = false;
      if (this.form.issuance_qty > this.form.balance) {
        this.errors = {
          error: [
            "Overissue: Issuance limit is up to" +
              this.form.balance +
              " only.!",
          ],
        };
        this.errors_exist = true;
      }
      if (this.form.issuance_qty == 0) {
        this.errors = {
          error: ["Cannot proceed with 0 issuance!"],
        };
        this.errors_exist = true;
      }
      if (!this.errors_exist) {
        const res = await this.submit("post", "/issuances/store", this.form, {
          headers: {
            "Content-Type":
              "multipart/form-data; charset=utf-8; boundary=" +
              Math.random().toString().substr(2),
          },
        });
        if (res.status === 200) {
          this.dialogRef.close(this.form);
        } else {
          this.errors_exist = true;
          this.errors = res.data.errors;
        }
      }
    },
  },
};
</script>