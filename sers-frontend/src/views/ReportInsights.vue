<template>
    <div class ="container-fluid display-container" v-if="access">
        <div class="row">
            <div class="col">
                <h5>Reports & Insights</h5>
                <p class = "text-muted mb-5">Generate reports to keep track of Course Enrichment Progress.</p>
            </div>
        </div>
        <div class="row top-table">
            <div class="col-6">
                <h5>View Statistics</h5>
                <table>
                    <tr>
                        <th>Select View:</th>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="yearly-statisics" v-model="yearlyStats">
                                <label class="custom-control-label" for="yearly-statisics">Yearly Statistics</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="course-statistics" v-model="courseStats">
                                <label class="custom-control-label" for="course-statistics">Course Statistics</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="popular-courses" v-model="popularCourses">
                                <label class="custom-control-label" for="popular-courses">Popular Courses</label>
                            </div>
                        </td>
                    </tr>
                </table>
                <button type = "button" class = "report-insights-btn" @click="changeTrigger()">Export Selected to .CSV</button>
            </div>
            <div class="col-6">
                <h5>Export Yearly Raw Data</h5>
                <table>
                    <tr>
                        <th>Select Years:</th>
                        <td>
                            <select class="custom-select" id="select-year" v-model="chosenYear" @change="filterData()">
                                <option v-for="year in years" :key="year" :value="year">{{year}}</option>
                            </select>
                        </td>
                        <td>
                            <button type = "button" class = "report-insights-btn preview" @click="displayTable()" >Preview</button>
                        </td>
                    </tr>
                </table>
                <template v-if="filteredData.length >0">
                    <div class = "preview-table">
                        <button @click="hideTable()" class="close-preview-btn"><i class="fas fa-times"></i></button>
                        <Preview :filteredData="filteredData"/>
                    </div>
                </template>
                <template v-else>
                    <h6 class = "no-preview"> No Preview</h6>
                </template>
                <button type = "button" class = "report-insights-btn right-btn" @click="exportData()">Export Raw Data to .CSV</button>
            </div>
        </div>
        <div v-if="yearlyStats">
            <YearlyStatistics :years='years' :trigger="yearlyTrigger"/>
        </div>
        <div v-if="courseStats">
            <CourseStatistics :years='years' :trigger="courseTrigger"/>
        </div>
        <div v-if="popularCourses">
            <PopularCourses :years='years' :trigger="popularTrigger"/>
        </div>
    </div>
</template>
<script>
    import YearlyStatistics from '../components/ReportInsights/YearlyStatistics'
    import CourseStatistics from '../components/ReportInsights/CourseStatistics'
    import PopularCourses from '../components/ReportInsights/PopularCourses'
    import Preview from '../components/ReportInsights/Preview'
    import {GETRAWDATA_URL, GETUSERROLE_URL} from '../variables'
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../router.js'
    export default{
        name: 'ReportInsights',
        data(){
            return{
                rawData: [],
                filteredData: [],
                yearlyStats: true,
                courseStats: true,
                popularCourses: true,
                years: [],
                chosenYear: 2021,
                yearlyTrigger: false,
                courseTrigger: false,
                popularTrigger: false,
                access: false
            }
        },
        components:{
            YearlyStatistics,
            CourseStatistics,
            PopularCourses,
            Preview
        },
        methods: {
            getRawData(){
                axios.get(GETRAWDATA_URL,
                {withCredentials: true}
                ).then(response=> (
                    this.rawData = response.data,
                    this.filterData()
                ))
            },
            filterData(){
                var ref = this
                const filtered_data = this.rawData.filter(function(row){
                    
                    if (row.Year == ref.chosenYear){
                        
                        return (row)
                    }
                })
                this.filteredData = filtered_data  
            },
            populateYears(){
                var d = new Date();
                var n = d.getFullYear()

                for (var i = 0; i < 4; i++){
                    this.years.push(n-i)
                }
            },
            changeTrigger(){
                if (this.yearlyStats){
                    this.yearlyTrigger = !this.yearlyTrigger
                }
                if (this.courseStats){
                    this.courseTrigger = !this.courseTrigger
                }
                if (this.popularCourses){
                    this.popularTrigger = !this.popularTrigger
                }

                console.log(this.yearlyTrigger, this.courseTrigger, this.popularTrigger)
            },
            displayTable(){
                document.getElementsByClassName('preview-table')[0].style.display = "block"
            },
            hideTable(){
                document.getElementsByClassName('preview-table')[0].style.display = "none"
            },
            exportData(){

                const data = this.filteredData.map(row=>({
                    'Year': row.Year,
                    'Course': row.Course_ID + " - " + row.Course_Name,
                    'Instructor': row.Instructor_ID + ' - ' + row.Instructor_Name,
                    'Instructor Email':  row.Instructor_Email,
                    'Organisation': row.Company? row.Company : "NIL",
                    'Alumni': row.Alumni? "Yes" : "No",
                    'Course Category': row.Category,
                    'Student': row.Student_ID+ " - " + row.Student_Name,
                    'Registration Status': row.Reg_Status,
                    'Completion Status': row.Completion_Status,
                    'Certification Status': row.Completion_Status == "completed"? "Awarded" : "NIL"
                }))
                // console.log(data)
                const csvText = this.objectToCSV(data)
                // console.log('text', csvText)
                this.downloadCSV(csvText)
            },
            objectToCSV(data){
                const csvRows = []

                const headers = Object.keys(data[0])

                csvRows.push(headers.join(','))


                for (const row of data){
                    const values = headers.map(header=>{
                        const escaped = (''+row[header]).replace(/"/g, '\\"')
                        return `"${escaped}"`
                    })

                    csvRows.push(values.join(','))

                }
   
                return csvRows.join('\n')
            },

            downloadCSV(data){
                const blob = new Blob([data], {type: 'text/csv'})
                const url = window.URL.createObjectURL(blob)
                const a = document.createElement('a')
                a.setAttribute('hidden', '')
                a.setAttribute('href', url)
                a.setAttribute('download', 'Raw_Data_'+ this.chosenYear+'.csv' )
                document.body.appendChild(a)
                a.click()
                document.body.removeChild(a)

            },

            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    
                    if (response.data != 2 && response.data != 4){
                        this.redirectToCourse()
                        router.push('/instructorcourses')

                    }else{
                        this.populateYears()
                        this.getRawData()
                        this.access = true
                        
                    }
                })
            },
            redirectToCourse(){
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
            },
        },
        mounted(){
            this.checkUserRole()
        }
    }
</script>
<style scoped>
    tr:hover{
        background-color:var(--navigation-color);
        color: black;
        cursor: default; 
    }
    .left-btn{
        margin-top:3em;
    }
    .right-btn{
        position:absolute;
        bottom:0;
    }
    .top-table{
        margin-bottom: 3em;
    }
    .preview-table{
        /* position:absolute; */
        z-index: 5;
        transition:0.2s;
        display: none;
    }
    .close-preview-btn{
        border: none;
        border-radius: 50%;
        color: var(--pill-red);
        transition: 0.2s;
    }
    .close-preview-btn:hover{
        transform: translateY(-2px);
    }
    .no-preview{
        color: var(--pill-red)
    }
    .row{
        margin-top:7em;
    }
</style>