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
        <div class="col-lg-12">
          <div
            v-for="history in histories"
            :key="history.id"
            class="form-group row"
          >
            <label for="posted-date" class="">
              Product Name : {{ history.item_code }}<br />
              Delivered/Received Quantity : {{ history.issuance_qty }} <br />
              Date Received : {{ history.created_at.substring(0,10) }}<br />
              Received By : {{ history.received_by }}<br />
              issued By :{{ history.issued_by }}<br />
              Remaining Balance : {{ history.balance }}<br />
            </label>

            <hr />
          </div>
        </div>
      </div>
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
      histories: {},
      form: {
        id: this.dialogRef.data.id,
      },
    };
  },
  created() {
    this.fetchItems();
  },
  methods: {
    closeDialog() {
      this.dialogRef.close();
    },
    async fetchItems() {
      const res = await this.callApiwParam(
        "post",
        "/issuances/getIssuanceHistory",
        this.form
      );
      this.histories = res.data;
    },
  },
};
</script>