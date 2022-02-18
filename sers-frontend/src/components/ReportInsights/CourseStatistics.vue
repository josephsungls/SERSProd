<template>
    <div>
        <div class = "table-container animate__animated animate__fadeIn animate__faster report-table">
            <div class="table-header">
                <h4>Course Statistics</h4>
                <select id="select-year" v-model="chosenYear">
                    <option v-for="year in years" :key="year" :value="year">{{year}}</option>
                </select>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course ID</th>
                        <th scope = "col">Course</th>
                        <th scope = "col">Certification</th>
                        <th scope = "col">No. of Students Registered</th>
                        <th scope = "col">No. of Slots Offered</th>
                        <th scope = "col">No. of Students Certified</th>
                        <th scope = "col">No. of Students Completed</th>
                        <th scope = "col">External/Internal Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr v-for="stat in statistics" :key="stat">
                        <template v-if="stat.Year == chosenYear">
                            <td>{{stat.Course_ID}}</td>
                            <td>{{stat.Course_Name}}</td>
                            <td>
                                <span v-if="stat.Certification_No">Yes</span>
                                <span v-else>No</span>
                            </td>
                            <td>
                                <span v-if="stat.No_Registrants">
                                    {{stat.No_Registrants}}
                                </span>
                                <span v-else>0</span>
                            </td>
                            <td>
                                <span v-if="stat.Slots_Offered">
                                    {{stat.Slots_Offered}}
                                </span>
                                <span v-else>
                                    NIL
                                </span>
                            </td>
                            <td>
                                <span v-if="stat.No_Completed && stat.Certification_No">
                                    {{stat.No_Completed}}
                                </span>
                                <span v-else>0</span>
                            </td>
                            <td>
                                <span v-if="stat.No_Completed">
                                    {{stat.No_Completed}}
                                </span>
                                <span v-else>0</span>
                            </td>
                            <td>
                                <span v-if="!stat.Slots_Offered">
                                    NIL
                                </span>
                                <span v-else-if="!stat.EUser_IDs">
                                    Internal
                                </span>
                                <span v-else-if="stat.EUser_IDs != stat.Instructor_IDs">Internal & External</span>
                                <span v-else>External</span>
                            </td>
                        </template>
                    </tr>
                </tbody>
            </table>
        </div>
        <button type="button" class="report-table-btn" @click='exportData(chosenYear)'>Export to .csv</button>
    </div>
</template>
<script>
    import axios from 'axios'
    import {GETCOURSESTATISTICS_URL} from '../../variables'
    export default{
        name: "CourseStatistics",
        data(){
            return{
                statistics:[],
                chosenYear: new Date().getFullYear()
            }
        },
        props:{
            years: Array,
            trigger: Boolean
        },
        methods:{
            getCourseStatistics(){
                axios.get(GETCOURSESTATISTICS_URL,
                {withCredentials: true}
                ).then(response => (
                    this.statistics = response.data
                ))
            },
            exportData(year){
                const filtered_statistics = this.statistics.filter(function(row){
                    
                    if (row.Year == year){
                        
                        return (row)
                    }
                })
   

                const data = filtered_statistics.map(row=>({
                    'Course ID': row.Course_ID,
                    'Course': row.Course_Name,
                    'Certification': row.Certification_No? 'Yes': 'No',
                    'No. of Students Registered': row.No_Registrants? row.No_Registrants : 0,
                    'No. of Slots Offered': row.Slots_Offered? row.Slots_Offered : 0,
                    'No. of Students Certified': row.No_Completed? row.No_Completed : 0,
                    'No. of Students Completed': row.No_Completed? row.No_Completed : 0,
                    'External / Internal Instructor': row.Slots_Offered? row.EUser_IDs? row.EUser_IDs == row.Instructor_IDs? 'External' : 'Internal & External' : 'Internal' : 'NIL',
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
                a.setAttribute('download', 'Course_Statistics_'+ this.chosenYear+'.csv' )
                document.body.appendChild(a)
                a.click()
                document.body.removeChild(a)

            }
        },
        mounted(){
            this.getCourseStatistics()
        },
        watch:{
            trigger (){
               this.exportData(this.chosenYear) 
            }
        }
    }
</script>
<style scoped>
</style>