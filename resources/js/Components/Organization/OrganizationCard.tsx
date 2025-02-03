import {Organization} from "@/types";
import {Link} from "@inertiajs/react";
import ProjectCard from "@/Components/Project/ProjectCard";

export default function OrganizationCard({ organization }: { organization: Organization }) {
    return (
        <div className="border-2 flex flex-col md:flex-row md:h-48 lg:h-52 no-underline rounded-md text-gray-500 overflow-hidden mb-4 lg:mb-6 border-blue-200">
            <Link href={route('organizations.show', organization.identifier)} className="no-underline flex h-full bg-white border-b-2 md:border-b-0 md:border-r-2 text-gray-700 hover:bg-blue-50 transition-colors duration-100 hover:text-blue-600 z-10 border-blue-200">
                <div className="flex flex-col justify-between p-4 h-full w-full md:w-72">
                    <div className="flex flex-col">
                        <div className="flex items-center justify-between w-full">

                            <div className="relative rounded-full text-white overflow-hidden border-yellow-500 flex items-center justify-center border-2 size-10">
                                <div className="rounded-full flex items-center justify-center border-2 border-white">
                                    {organization.icon ? (
                                        <img className="object-cover object-center rounded-full" src={organization.icon} alt={organization.name}/>
                                    ) : (
                                        <img className="object-cover object-center rounded-full" src="/assets/images/org-icon-placeholder.png" alt={organization.name}/>
                                    )}
                                </div>
                            </div>

                            <Link href={route('organizations.show', organization.identifier)} className="inline-flex items-start rounded leading-none border-solid border transition-colors duration-150 min-h-4 bg-green-50 hover:bg-green-100 border-green-200 text-green-600">
                                <div className="px-1 py-[2px]">
                                    <div className="flex items-center justify-center gap-x-1 leading-tight px-2 text-sm text-gray-700 fill-gray-700">
                                        <div className="flex items-center gap-x-[2px]">
                                            <span className="text-green-600">0</span> / <span>10,000</span>
                                        </div>
                                        <svg className="size-4 transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M0 0h24v24H0V0z" fill="none"></path>
                                            <path d="M22 19h-6v-4h-2.68c-1.14 2.42-3.6 4-6.32 4-3.86 0-7-3.14-7-7s3.14-7 7-7c2.72 0 5.17 1.58 6.32 4H24v6h-2v4zm-4-2h2v-4h2v-2H11.94l-.23-.67C11.01 8.34 9.11 7 7 7c-2.76 0-5 2.24-5 5s2.24 5 5 5c2.11 0 4.01-1.34 4.71-3.33l.23-.67H18v4zM7 15c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3zm0-4c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </Link>
                        </div>

                        <div className="md:flex-1 text-lg my-1 md:mb-0 md:mt-2 max-h-14 lg:max-h-24 overflow-hidden font-medium">
                            {organization.name ?? 'Unnamed Organization'}
                        </div>
                    </div>

                    <div className="flex justify-between items-center w-full">
                        <div className="flex flex-col w-full leading-tight">
                            <div className="flex md:justify-between items-center">
                                <div className="text-gray-500 text-sm"> Owner</div>
                            </div>
                            <div className="text-gray-400 text-sm"> No project added</div>
                        </div>
                        <div className="flex gap-x-1"></div>
                    </div>
                </div>
            </Link>

            <div className="w-full p-4 h-48 flex gap-4 flex-grow sm:h-full bg-gray-100 hover:bg-gradient-to-r overflow-y-auto from-gray-50 via-gray-100 to-gray-50">
                <div className="h-full w-72 shrink-0">
                    <Link href={route('projects.create', organization.identifier)} className="no-underline flex flex-col divide-y divide-blue-100 border-2 hover:shadow-sm transition-all border-blue-100 hover:border-blue-200 rounded-md duration-100 h-full overflow-hidden">
                        <div className="py-3 px-4 flex-1 bg-blue-50">
                            <div className="text-2xl lg:text-4xl">
                                <svg className="size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36">
                                    <path fill="#FDD888" d="M28.865 7.134c7.361 7.359 9.35 17.304 4.443 22.209-4.907 4.907-14.85 2.918-22.21-4.441-.25-.25-.478-.51-.716-.766l4.417-4.417c5.724 5.724 13.016 7.714 16.286 4.442 3.271-3.271 1.282-10.563-4.441-16.287l.022.021-.021-.022C20.104 1.331 11.154-.326 6.657 4.171 4.482 6.346 3.76 9.564 4.319 13.044c-.858-4.083-.15-7.866 2.338-10.353 4.906-4.906 14.849-2.917 22.208 4.443z"/>
                                    <path fill="#FFAC33" d="M19.403 34c-.252 0-.503-.077-.719-.231l-5.076-3.641-5.076 3.641c-.433.31-1.013.31-1.443-.005-.43-.312-.611-.864-.45-1.369l1.894-6.11-5.031-3.545c-.428-.315-.605-.869-.442-1.375.165-.504.634-.847 1.165-.851l6.147-.012 2.067-5.957c.168-.504.639-.844 1.17-.844.531 0 1.002.34 1.17.844l1.866 5.957 6.347.012c.532.004 1.002.347 1.165.851.164.506-.014 1.06-.442 1.375l-5.031 3.545 1.893 6.11c.162.505-.021 1.058-.449 1.369-.217.158-.471.236-.725.236z"/>
                                </svg>
                            </div>

                            <div className="text-gray-500 text-sm mt-1 lg:mt-2">
                                Create a new project and start your localization journey.
                            </div>
                        </div>
                        <div className="flex items-center justify-between px-4 no-underline w-full bg-white hover:bg-blue-50 transition-colors duration-100 h-12">
                            <div className="text-blue-600 text-sm font-medium uppercase tracking-wide">
                                Create new project
                            </div>
                            <svg className="size-4 transform fill-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path>
                                <path d="M0 0h24v24H0z" fill="none"></path>
                            </svg>
                        </div>
                    </Link>
                </div>

                {organization.projects.map((project) => (
                    <ProjectCard project={project} key={project.id}/>
                ))}
            </div>
        </div>
    );
}