<template>
    <div class = "container-fluid  display-container">
        <div class="table-container">
            
            <div class="table-header">
                <h4>Course Database</h4>
                <p class = "text-muted">Create and update courses.</p>
            </div>
            
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col">Course Description</th>
                        <th scope = "col">Category</th>
                        <th scope = "col" class = "status text-center">Course Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="course in courses_data" :key="course" @click="changeView(course.Course_ID)">
                        <td  class="update" data-toggle="modal" data-target="#updateCourse"><b>{{course.Course_ID}}</b> <br> {{course.Course_Name}}</td>
                        <td class="update" data-toggle="modal" data-target="#updateCourse">{{course.Course_Desc}}</td>
                        <td class="update" data-toggle="modal" data-target="#updateCourse">{{course.Category}}</td>
                        <td class = "text-center" >
                            <span :class="['badge', 'badge-pill', course.Course_Status, 'more']" data-toggle="modal" data-target="#updateStatus">{{course.Course_Status}}</span>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>
<script>
    import axios from 'axios'
    import {COURSES_URL} from '../../../variables'
    import router from '/src/router.js'
    export default{
        name: "CourseDB",
        data(){
            return{ 
                current: [],
                courses_data: [],
                current_update: [],
                alert: '',
                
            }
        },
        props:{

        },
        methods:{
            getCourses(){
                axios.get(COURSES_URL).then(response => (
                    this.courses_data = response.data
                ))
            },
            changeView(course_id){
                router.push('/coursehistory/'+ course_id)
            }
        },
        mounted(){
            this.getCourses()
        },

    }
</script>
<style scoped>

    .badge-pill{
        width: 100px;
    }
    :deep(.active){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.voting){
        background-color: var(--pill-blue);
        color: white;
    }
    :deep(.registering){
        background-color: var(--pill-yellow);
        color: white;
    }
    :deep(.not.offered){
        background-color: var(--dark-gray);
        color: white;
    }
    :deep(.retired){
        background-color: var(--pill-red);
        color: white;
    }
    table{
        min-width: 800px;
    }
    thead{
        top:0;
        position:sticky;
        
        background:var(--navigation-color);
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.2);
    }
    .more{
        transition:0.2s;
        /* color: var(--dark-gray); */
        margin-right:3em;
        text-align: center;
        cursor: pointer;

    }
    .more:hover{
        transform: translateY(-2px);
        /* color: black; */
    }
    .status{
        width:200px;
    }
    .modal-btn{
        margin: 5px 5px;
    }
    
    .add-course{
        display:flex;
        width: 159px;
        height: 52px;
        font-weight: bold;
        border-radius: 10px;
        background-color: var(--navigation-color);
        border: 2px solid var(--light-gray);
        margin-bottom: 20px;
        transition: 0.2s;
        /* margin:0; */

    }
    .add-course a{
        text-decoration: none;
        display:flex;
        color: var(--font-color);
        transition:0.2s;
    }
    .add-course:hover a{
        color: var(--navigation-color);
    }
    .add-course:hover,
    .add-course:hover i
     {
        color: var(--navigation-color);
        background-color: var(--button-color);
        border: 2px solid var(--navigation-color);
        /*  */
    }
    .add-course i{  
        margin-left: 5px;
        align-self: center;
        border: 2px solid var(--button-color);
        padding:3px;
        color:var(--button-color);
        border-radius: 50%;
        transition: 0.2s;
    }
    .add-course:focus{
        outline:none;
        box-shadow:none;
    }

    .update{
        cursor: pointer;

        /* transition:0.2s; */
    }
    td {
        transition:0.1s;
    }
    tr:hover > td{
        background-color:var(--button-color);
        color:var(--navigation-color);
    }
</style>