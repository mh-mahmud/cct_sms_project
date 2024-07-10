import VueRouter from 'vue-router';
import userRoutes from './user';
import contactRoutes from './contact';
import templateRoutes from './template';
import scheduleRoutes from './schedule';
import historyRoutes from './history';
import composeRoutes from './compose';
import textRoutes from './text';
import outboundRoutes from './outbound';
import dashboardRoutes from './dashboard';
import groupRoutes from './group';
import inboxRoutes from './inbox';
import auditLogRoutes from './auditLog';
// Admin Routes
import adminDashboardRoutes from './adminDashboard';
import adminTextRoutes from './adminText';
import adminOutboundRoutes from './adminOutbound';
import adminAuditLogRoutes from './adminAuditLog';
import adminContact from './adminContact';
import adminGroup from './adminGroup';
import adminTemplate from './adminTemplate';
import adminHistory from './adminHistory';
import adminSchedule from './adminSchedule';
// Admin Routes

const baseRoutes = [];
const routes = baseRoutes.concat(userRoutes, dashboardRoutes, contactRoutes, groupRoutes, templateRoutes, scheduleRoutes, historyRoutes, composeRoutes, textRoutes, outboundRoutes, inboxRoutes, auditLogRoutes, adminDashboardRoutes,adminTextRoutes,adminOutboundRoutes,adminAuditLogRoutes, adminContact, adminGroup, adminTemplate, adminHistory, adminSchedule);

export default new VueRouter({
    routes,
});