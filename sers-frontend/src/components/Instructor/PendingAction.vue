<template>
    <div class="table-container">
        <div class="table-header">
            <h4>Course Database</h4>
            <p class = "text-muted">Courses pending your actions, update the relevant information so the course can be opened for registrations.</p>
        </div>
        <table class = "table">
            <thead>
                <tr>
                    <th scope = "col">Course Code & Name</th>
                    <th scope = "col">Course Description</th>
                    <th scope = "col">Category</th>
                    <th scope = "col">Status</th>
                    <!-- <th scope = "col" class = "status text-center">Course Status</th> -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="course in assigned_runs" :key="course">
                    <td  @click="enterCourse(course)"><b>{{course.RunCourse_ID}}</b> <br> {{course.Course_Name}} - Class:{{course.Class}}</td>
                    <td  @click="enterCourse(course)">  <br> {{course.Run_Desc}}</td>
                    <td  @click="enterCourse(course)"> <br> {{course.Category}}</td>
                    <td  @click="enterCourse(course)">
                        <br/>
                        <span :class="['badge', 'badge-pill', course.Run_Status, 'instructor-pill']">
                            <span v-if="course.Run_Status == 'pending'">WAITING FOR INSTRUCTOR</span>
                            <span v-else>INSTRUCTOR READY</span>
                        </span>
                    </td>
                    <!-- <td class = "text-center">
                        <br> <span :class="['badge', 'badge-pill', course.Run_Status== 'pending'? 'gray':'updated']">Open</span>
                    </td>
                     -->
                </tr>
            </tbody>
        </table>



    </div>
</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../../router'
    import {GETINSTRUCTORRUN_URL} from '../../variables'
    export default{
        name: 'PendingAction',
        data(){
            return{
                assigned_runs:[],
            }
        },
        methods:{
            getAssignedRuns(){
                axios.post(GETINSTRUCTORRUN_URL,
                {},
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data === 'no rights'){
                        let timerInterval
                        Swal.fire({
                            icon: 'error',
                            title: 'Access denied!',
                            html: 'You do not have the access rights! Redirecting...',
                            timer: 1000,
                            timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                        })
                        router.push('/courses')
                    }
                    if (response.data === 'updated'){
                        this.update_values = {...this.new_values}
                        Swal.fire({
                        icon: 'success',
                        title: 'Indicated',
                        text: 'The coordinator can now open the course up for registration ',
                        showConfirmButton: false,
                        timer: 1500
                        })
                        this.goBack()
                    }
                    this.assigned_runs = response.data
                })
            },
            enterCourse(course){
                this.$parent.current = course
                this.$parent.individualCourse()
            }
        },
        mounted(){
            this.getAssignedRuns()
        }
    }
</script>
<style scoped>
    .badge-pill{
        width: 100px;
    }
    :deep(.pending){
        background-color: var(--pill-yellow);
        color: white;
    }
    :deep(.updated){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.gray){
        background-color: var(--gray);
        color: white;
    }
    .instructor-pill{
        width:200px;
        /* font-size:5px; */
    }
    td {
        transition:0.1s;
        cursor: pointer;
    }
    tr:hover > td{
        background-color:var(--button-color);
        color:var(--navigation-color);
    }
.table-container{
        width: 75%;
    }
</style>