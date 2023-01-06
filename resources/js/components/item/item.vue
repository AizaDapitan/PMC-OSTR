<template>
  <div v-if="errors_exist" class="alert alert-danger" role="alert">
    Whoops! Something didn't work.
    <ul>
      <div v-for="error in errors" :key="error.id">
        <li>{{ error[0] }}</li>
      </div>
    </ul>
  </div>
  <form role="form" method="GET" action="">
    <div class="modal-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group">
            <label for="stock-code">Stock Code</label>
            <input
              class="form-control input-sm"
              v-model="form.stock_code"
              disabled="true"
            />
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label for="available-qty"> Available Qty </label>
            <input
              class="form-control input-sm"
              v-model="form.available_qty"
              disabled="true"
            />
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label for="uom"> Uom </label>
            <input
              class="form-control input-sm"
              v-model="form.uom"
              disabled="true"
            />
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label for="description"> Description<span class="text-danger" aria-required="true">
                  *
                </span> </label>
            <AutoComplete
              v-model="selectedProduct"
              :suggestions="filteredProducts"
              @complete="searchProduct($event)"
              style="width: 100%"
              inputStyle="width:100%"
              field="description"
              @item-select="onChange($event)"
            />
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label for="requested-qty"> Requested Qty. <span class="text-danger" aria-required="true">
                  *
                </span> </label>
            <input
              type="number"
              class="form-control input-sm"
              v-model="form.requested_qty"
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
      form: {
        id: this.dialogRef.data.id,
        stock_code: this.dialogRef.data.stock_code,
        available_qty: this.dialogRef.data.available_qty,
        uom: this.dialogRef.data.uom,
        description: this.dialogRef.data.description,
        requested_qty: this.dialogRef.data.requested_qty,
        requested_by: this.dialogRef.data.requested_by,
      },
    };
  },
  mounted() {},
  methods: {
    closeDialog() {
      this.dialogRef.close();
    },
    async saveItem() {
      this.errors = {};
      this.errors_exist = false;
      if (this.form.available_qty < this.form.requested_qty) {
        this.errors = {
          error: ["Available Qty. is not enough for Requested Qty.!"],
        };
        this.errors_exist = true;
      }
      if (!this.errors_exist) {
        var url = "/requested_items/store";
        if (this.form.id != undefined) {
          url = "/requested_items/update";
        }
        const res = await this.submit("post", url, this.form, {
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
    searchProduct(event) {
      setTimeout(() => {
        if (!event.query.trim().length) {
          this.filteredProducts = [...this.products];
        } else {
          this.filteredProducts = this.products.filter((product) => {
            return product.description
              .toLowerCase()
              .startsWith(event.query.toLowerCase());
          });
        }
      }, 250);
    },
    onChange(event) {
      this.form.stock_code = this.selectedProduct.stock_code;
      this.form.available_qty = this.selectedProduct.available_qty;
      this.form.description = this.selectedProduct.description;
      this.form.uom = this.selectedProduct.uom;
      this.form.requested_qty = "";
    },
  },
};
</script>