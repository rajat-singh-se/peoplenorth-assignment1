import { createWebHistory, createRouter } from 'vue-router'

// Vue.use(Router)

const routes = [
    {
      path: '/employee',
      name: 'Employee',
      component:()=>import('./components/Employees/employeeResports.vue')
    },
    {
        path: '/employee/create',
        name: 'create',
        component:()=>import('./components/Employees/createEmployees.vue')
      },
      {
        path: '/employee/edit/:id',
        name: 'edit',
        component:()=>import('./components/Employees/createEmployees.vue')
      }
  ]

const router = createRouter({
	history: createWebHistory(),
	routes,
})
export default router
