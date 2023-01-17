require('./bootstrap');

window.Vue = require("vue").default;
import { createApp } from "vue";
import common from "./common";
import PrimeVue from "primevue/config";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";

import "primevue/resources/themes/saga-blue/theme.css";
import "primevue/resources/primevue.min.css";   
import Toolbar from "primevue/toolbar";
import Toast from "primevue/toast";
import ToastService from "primevue/toastservice";
import DialogService from 'primevue/dialogservice';
import ConfirmationService from "primevue/confirmationservice";
import ConfirmDialog from "primevue/confirmdialog";
import Dropdown from "primevue/dropdown";
import DynamicDialog from 'primevue/dynamicdialog';
import Tooltip from 'primevue/tooltip';
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
// import 'primeicons/primeicons.css';

const app = createApp({});
app.mixin(common);
app.use(PrimeVue);
app.use(DialogService);
app.use(ConfirmationService);
app.use(ToastService);
app.directive('tooltip', Tooltip);

app.config.globalProperties.$env_Url = process.env.MIX_APP_URL;

app.component(
    "login-component",
    require("./components/auth/login.vue").default
);
app.component(
    "adminlogin-component",
    require("./components/auth/adminlogin.vue").default
);
app.component(
    "change_password-component",
    require("./components/auth/change_password.vue").default
);
app.component(
    "forgot_password-component",
    require("./components/auth/forgot_password.vue").default
);


app.component(
    "create_role-component",
    require("./components/role/create_role.vue").default
);
app.component(
    "index_role-component",
    require("./components/role/index_role.vue").default
);
app.component(
    "edit_role-component",
    require("./components/role/edit_role.vue").default
);

app.component(
    "create_permission-component",
    require("./components/permission/create_permission.vue").default
);
app.component(
    "index_permission-component",
    require("./components/permission/index_permission.vue").default
);
app.component(
    "edit_permission-component",
    require("./components/permission/edit_permission.vue").default
);

app.component(
    "index_user-component",
    require("./components/user/index_user.vue").default
);
app.component(
    "create_user-component",
    require("./components/user/create_user.vue").default
);
app.component(
    "edit_user-component",
    require("./components/user/edit_user.vue").default
);

app.component(
    "useraccess_rights-component",
    require("./components/accessrights/useraccess_rights.vue").default
);
app.component(
    "roleaccess_rights-component",
    require("./components/accessrights/roleaccess_rights.vue").default
);
app.component(
    "audit_logs-component",
    require("./components/report/audit_logs.vue").default
);
app.component(
    "error_logs-component",
    require("./components/report/error_logs.vue").default
);

app.component(
    "index_application-component",
    require("./components/application/index_application.vue").default
);
app.component(
    "create_application-component",
    require("./components/application/create_application.vue").default
);
app.component(
    "edit_application-component",
    require("./components/application/edit_application.vue").default
);

app.component(
    "stockrequest_index-component",
    require("./components/stockrequest/index.vue").default
);
app.component(
    "stockrequest_create-component",
    require("./components/stockrequest/create.vue").default
);
app.component(
    "stockrequest_edit-component",
    require("./components/stockrequest/edit.vue").default
);
app.component(
    "stockrequest_view-component",
    require("./components/stockrequest/view.vue").default
);
app.component(
    "stockrequest_dashboard-component",
    require("./components/stockrequest/dashboard.vue").default
);
app.component(
    "stockrequest_unsaved-component",
    require("./components/stockrequest/unsaved.vue").default
);

app.component(
    "approval_index-component",
    require("./components/approval/index.vue").default
);
app.component(
    "approval_view-component",
    require("./components/approval/view.vue").default
);
app.component(
    "approval_checkdetails-component",
    require("./components/approval/checkdetails.vue").default
);

app.component(
    "mcd_index-component",
    require("./components/mcd/index.vue").default
);
app.component(
    "mcd_view-component",
    require("./components/mcd/view.vue").default
);
app.component(
    "mcd_edit-component",
    require("./components/mcd/edit.vue").default
);

app.component("DataTable", DataTable);
app.component("Column", Column);
app.component("Toolbar", Toolbar);
app.component("Button", Button);
app.component("Toast", Toast);
app.component("ConfirmationService", ConfirmationService);
app.component("ConfirmDialog", ConfirmDialog);
app.component("DynamicDialog", DynamicDialog);
app.component("Dropdown", Dropdown);
app.component("Calendar", Calendar);
app.component("AutoComplete", AutoComplete);
app.mount("#app");

