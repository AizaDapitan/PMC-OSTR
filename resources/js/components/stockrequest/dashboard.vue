<template>
  <div class="container-fluid pd-x-0">
    <div
      class="
        d-sm-flex
        align-items-center
        justify-content-between
        mg-b-20 mg-lg-b-25 mg-xl-b-30
      "
    >
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">OSTR</li>
            <li class="breadcrumb-item" aria-current="page">Stock Request</li>
            <li class="breadcrumb-item active" aria-current="page">
              Dashboard
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Welcome {{ username }}!</h4>
      </div>
    </div>

    <div class="row row-xs">
      <!-- Start Pages -->

      <div class="col-md-12">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="tx-bold mg-b-5 lh-1 mb-3">Pending Request</h5>
            <div class="table-list mg-b-10">
              <div class="table-responsive-lg">
                <DataTable
                  ref="dt"
                  :value="pending"
                  :paginator="true"
                  :rows="10"
                  stripedRows
                  removableSort
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  :rowsPerPageOptions="[10, 20, 50]"
                  responsiveLayout="scroll"
                  :loading="loading"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                  v-model:filters="filters"
                  filterDisplay="menu"
                  :globalFilterFields="[
                    'transaction_no',
                    'dept',
                    'date_filed',
                    'time_filed',
                    'date_needed',
                    'status',
                    'cost_code',
                  ]"
                >
                  <template #empty> No record found. </template>
                  <template #loading> Loading data. Please wait. </template>
                  <Column field="id" hidden="true"></Column>
                  <Column
                    field="transaction_no"
                    header="Transaction #"
                    :sortable="true"
                    style="min-width: 11rem"
                  ></Column>
                  <Column
                    field="cost_code"
                    header="Cost Code"
                    :sortable="true"
                    style="min-width: 11rem"
                  ></Column>
                  <Column
                    field="date_filed"
                    header="Date Requested"
                    :sortable="true"
                    style="min-width: 9rem"
                  >
                  </Column>
                  <Column
                    field="time_filed"
                    header="Time Requested"
                    :sortable="true"
                    style="min-width: 5rem"
                  >
                    <template #body="slotProps">
                      <span>{{
                        slotProps.data.time_filed.replace(":00.0000000", "")
                      }}</span>
                    </template></Column
                  >
                  <Column
                    field="dept"
                    header="Department"
                    :sortable="true"
                    style="min-width: 10rem"
                  ></Column>
                  <Column
                    field="date_needed"
                    header="Date Needed"
                    :sortable="true"
                  ></Column>
                  <Column field="status" header="Status" :sortable="true">
                    <template #body="slotProps">
                      <span class="badge badge-danger tx-uppercase"
                        >Pending</span
                      >
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </div>
        </div>
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="tx-bold mg-b-5 lh-1 mb-3">In-progress Request</h5>
            <div class="table-list mg-b-10">
              <div class="table-responsive-lg">
                <DataTable
                  ref="dt"
                  :value="inprogress"
                  :paginator="true"
                  :rows="10"
                  stripedRows
                  removableSort
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  :rowsPerPageOptions="[10, 20, 50]"
                  responsiveLayout="scroll"
                  :loading="loading"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                  v-model:filters="filters"
                  filterDisplay="menu"
                  :globalFilterFields="[
                    'transaction_no',
                    'dept',
                    'date_filed',
                    'time_filed',
                    'date_needed',
                    'status',
                    'cost_code',
                  ]"
                >
                  <template #empty> No record found. </template>
                  <template #loading> Loading data. Please wait. </template>
                  <Column field="id" hidden="true"></Column>
                  <Column
                    field="transaction_no"
                    header="Transaction #"
                    :sortable="true"
                    style="min-width: 11rem"
                  ></Column>
                  <Column
                    field="cost_code"
                    header="Cost Code"
                    :sortable="true"
                    style="min-width: 11rem"
                  ></Column>
                  <Column
                    field="date_filed"
                    header="Date Requested"
                    :sortable="true"
                    style="min-width: 9rem"
                  >
                  </Column>
                  <Column
                    field="time_filed"
                    header="Time Requested"
                    :sortable="true"
                    style="min-width: 5rem"
                  >
                    <template #body="slotProps">
                      <span>{{
                        slotProps.data.time_filed.replace(":00.0000000", "")
                      }}</span>
                    </template></Column
                  >
                  <Column
                    field="dept"
                    header="Department"
                    :sortable="true"
                    style="min-width: 10rem"
                  ></Column>
                  <Column
                    field="date_needed"
                    header="Date Needed"
                    :sortable="true"
                  ></Column>
                  <Column field="status" header="Status" :sortable="true">
                    <template #body="slotProps">
                      <span
                        v-if="slotProps.data.status == 'Approved'"
                        class="badge badge-warning tx-uppercase"
                        >Approved</span
                      >
                      <span v-else class="badge badge-info tx-uppercase"
                        >Received</span
                      >
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </div>
        </div>
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="tx-bold mg-b-5 lh-1 mb-3">Completed Request</h5>
            <div class="table-list mg-b-10">
              <div class="table-responsive-lg">
                <DataTable
                  ref="dt"
                  :value="completed"
                  :paginator="true"
                  :rows="10"
                  stripedRows
                  removableSort
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  :rowsPerPageOptions="[10, 20, 50]"
                  responsiveLayout="scroll"
                  :loading="loading"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                  v-model:filters="filters"
                  filterDisplay="menu"
                  :globalFilterFields="[
                    'transaction_no',
                    'dept',
                    'date_filed',
                    'time_filed',
                    'date_needed',
                    'status',
                    'cost_code',
                  ]"
                >
                  <template #empty> No record found. </template>
                  <template #loading> Loading data. Please wait. </template>
                  <Column field="id" hidden="true"></Column>
                  <Column
                    field="transaction_no"
                    header="Transaction #"
                    :sortable="true"
                    style="min-width: 11rem"
                  ></Column>
                  <Column
                    field="cost_code"
                    header="Cost Code"
                    :sortable="true"
                    style="min-width: 11rem"
                  ></Column>
                  <Column
                    field="date_filed"
                    header="Date Requested"
                    :sortable="true"
                    style="min-width: 9rem"
                  >
                  </Column>
                  <Column
                    field="time_filed"
                    header="Time Requested"
                    :sortable="true"
                    style="min-width: 5rem"
                  >
                    <template #body="slotProps">
                      <span>{{
                        slotProps.data.time_filed.replace(":00.0000000", "")
                      }}</span>
                    </template></Column
                  >
                  <Column
                    field="dept"
                    header="Department"
                    :sortable="true"
                    style="min-width: 10rem"
                  ></Column>
                  <Column
                    field="date_needed"
                    header="Date Needed"
                    :sortable="true"
                  ></Column>
                  <Column field="status" header="Status" :sortable="true">
                    <template #body="slotProps">
                      <span class="badge badge-success tx-uppercase"
                        >Completed</span
                      >
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Pages -->
    </div>
    <div class="cms-footer mg-t-50">
      <hr />
      <p class="tx-gray-500 tx-10">
        Admin Portal v1.0 • Developed by WebFocus Solutions, Inc. 2022
      </p>
    </div>
  </div>

  <toast
    :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
  ></toast>
  <ConfirmDialog></ConfirmDialog>
</template>
      <script>
import { FilterMatchMode, FilterOperator } from "primevue/api";
export default {
  props: ["user"],
  data() {
    return {
      requests: [],
      pending: [],
      inprogress: [],
      completed: [],
      filters: null,
      loading: true,
      username: this.user.username.toUpperCase(),
    };
  },
  created() {
    this.fetchRecord();
    this.initFilters();
  },
  methods: {
    async fetchRecord() {
      const res = await this.getDataFromDB("get", "/stockrequests/getRequests-dashboard");
      this.requests = res.data;
      this.pending = this.requests.filter((request) => {
        return request.status.toLowerCase() == "pending";
      });
      this.inprogress = this.requests.filter((request) => {
        return (
          request.status.toLowerCase() == "received" ||
          request.status.toLowerCase() == "approved"
        );
      });
      this.completed = this.requests.filter((request) => {
        return (
          request.status.toLowerCase() == "completed" 
        );
      });
      this.loading = false;
    },
    initFilters() {
      this.filters = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      };
    },
    clearFilter() {
      this.filters["global"].value = null;
    },
    exportCSV() {
      this.$refs.dt.exportCSV();
    },
  },
};
</script>