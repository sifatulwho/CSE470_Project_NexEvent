<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Interactive Command Center</h2>
                <p class="text-sm text-gray-500">Hi {{ auth()->user()->name }}, here is how your experiences are performing this {{ strtolower(__('Week')) }}.</p>
            </div>
            <div class="flex flex-wrap gap-3 text-sm">
                <span class="inline-flex items-center rounded-full bg-indigo-100 px-4 py-2 font-medium text-indigo-700">Live attendees: <span class="ml-2 font-semibold">1,984</span></span>
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-4 py-2 font-medium text-emerald-700">Satisfaction: <span class="ml-2 font-semibold">96%</span></span>
                <a href="#upcoming" class="inline-flex items-center rounded-full border border-gray-200 px-4 py-2 font-semibold text-gray-600 hover:border-indigo-400 hover:text-indigo-600">Quick planner</a>
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8" x-data="{
        timeframe: 'weekly',
        showPlanner: false,
        newSession: { title: '', date: '', location: '' },
        metrics: {
            weekly: {
                revenue: '$24,600',
                conversions: '312 sign-ups',
                attendees: '1,280 attendees',
                satisfaction: '94% positive',
                growth: '+12.4% vs last week',
                insight: 'Peak momentum is building around the mid-week product preview. Keep sharing highlights to maintain this lift.',
                trend: [
                    { label: 'Mon', value: 52 },
                    { label: 'Tue', value: 68 },
                    { label: 'Wed', value: 74 },
                    { label: 'Thu', value: 91 },
                    { label: 'Fri', value: 83 },
                    { label: 'Sat', value: 56 },
                    { label: 'Sun', value: 42 }
                ]
            },
            monthly: {
                revenue: '$98,300',
                conversions: '1,284 sign-ups',
                attendees: '5,940 attendees',
                satisfaction: '92% positive',
                growth: '+19.7% vs last month',
                insight: 'Campaign retargeting has boosted interest—double down on sponsor spotlights and attendee testimonials.',
                trend: [
                    { label: 'Week 1', value: 48 },
                    { label: 'Week 2', value: 66 },
                    { label: 'Week 3', value: 79 },
                    { label: 'Week 4', value: 88 }
                ]
            },
            quarterly: {
                revenue: '$268,900',
                conversions: '3,782 sign-ups',
                attendees: '16,420 attendees',
                satisfaction: '95% positive',
                growth: '+27.3% vs last quarter',
                insight: 'Hybrid formats are outperforming in-person experiences. Allocate more budget to interactive livestream segments.',
                trend: [
                    { label: 'Jan', value: 62 },
                    { label: 'Feb', value: 75 },
                    { label: 'Mar', value: 94 }
                ]
            }
        },
        pipelines: [
            { title: 'Venue confirmed', percent: 92, color: 'bg-emerald-500' },
            { title: 'Speakers contracted', percent: 76, color: 'bg-indigo-500' },
            { title: 'Sponsorship fulfilled', percent: 64, color: 'bg-purple-500' },
            { title: 'Marketing campaigns', percent: 58, color: 'bg-amber-500' }
        ],
        tasks: [
            { title: 'Finalize catering vendor selection', owner: 'Alex', due: 'Today • 3:00 PM', done: false },
            { title: 'Schedule rehearsal walkthrough', owner: 'Priya', due: 'Tomorrow • 11:30 AM', done: false },
            { title: 'Approve stage design mockups', owner: 'Jamie', due: 'Nov 12 • 9:00 AM', done: true },
            { title: 'Publish attendee travel guide', owner: 'Morgan', due: 'Nov 13 • 1:00 PM', done: false }
        ],
        upcoming: [
            { title: 'Product Launch Summit', date: 'Nov 18', location: 'Skyline Pavilion', status: 'Planning' },
            { title: 'Investor Q&A Brunch', date: 'Nov 24', location: 'Willow Hall', status: 'Coordinating' },
            { title: 'Community Meetup Series', date: 'Dec 2', location: 'Downtown Hub', status: 'Ticketing' }
        ]
    }">
        <div class="grid gap-6 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-8">
                <section id="upcoming" class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xl sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Engagement overview</h3>
                            <p class="text-sm text-gray-500">Switch the timeframe to compare how your momentum stacks up.</p>
                        </div>
                        <div class="inline-flex shrink-0 overflow-hidden rounded-full border border-gray-200 bg-gray-50 p-1">
                            <button type="button" class="rounded-full px-4 py-2 text-xs font-semibold" :class="timeframe === 'weekly' ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:text-indigo-600'" @click="timeframe = 'weekly'">Weekly</button>
                            <button type="button" class="rounded-full px-4 py-2 text-xs font-semibold" :class="timeframe === 'monthly' ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:text-indigo-600'" @click="timeframe = 'monthly'">Monthly</button>
                            <button type="button" class="rounded-full px-4 py-2 text-xs font-semibold" :class="timeframe === 'quarterly' ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:text-indigo-600'" @click="timeframe = 'quarterly'">Quarterly</button>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-indigo-100 bg-indigo-50 px-4 py-5">
                            <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Revenue</p>
                            <p class="mt-3 text-2xl font-semibold text-indigo-900" x-text="metrics[timeframe].revenue"></p>
                            <p class="mt-1 text-xs font-medium text-indigo-600" x-text="metrics[timeframe].growth"></p>
                        </div>
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-5">
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-500">Conversions</p>
                            <p class="mt-3 text-2xl font-semibold text-emerald-900" x-text="metrics[timeframe].conversions"></p>
                            <p class="mt-1 text-xs font-medium text-emerald-600">Registrations flowing in steadily.</p>
                        </div>
                        <div class="rounded-2xl border border-purple-100 bg-purple-50 px-4 py-5">
                            <p class="text-xs font-semibold uppercase tracking-wide text-purple-500">Attendees</p>
                            <p class="mt-3 text-2xl font-semibold text-purple-900" x-text="metrics[timeframe].attendees"></p>
                            <p class="mt-1 text-xs font-medium text-purple-600">Blend of in-person &amp; virtual guests.</p>
                        </div>
                        <div class="rounded-2xl border border-amber-100 bg-amber-50 px-4 py-5">
                            <p class="text-xs font-semibold uppercase tracking-wide text-amber-500">Satisfaction</p>
                            <p class="mt-3 text-2xl font-semibold text-amber-900" x-text="metrics[timeframe].satisfaction"></p>
                            <p class="mt-1 text-xs font-medium text-amber-600">Feedback pulse remains high.</p>
                        </div>
                    </div>

                    <div class="mt-10">
                        <div class="flex items-end gap-3" :class="timeframe === 'weekly' ? 'h-48' : timeframe === 'monthly' ? 'h-52' : 'h-56'">
                            <template x-for="point in metrics[timeframe].trend" :key="point.label">
                                <div class="flex flex-1 flex-col items-center">
                                    <div class="w-full rounded-t-2xl bg-gradient-to-t from-indigo-200 via-indigo-400 to-indigo-600" :style="'height:' + point.value + '%'"></div>
                                    <span class="mt-3 text-xs font-medium text-gray-500" x-text="point.label"></span>
                                </div>
                            </template>
                        </div>
                        <p class="mt-6 text-sm text-gray-500" x-text="metrics[timeframe].insight"></p>
                    </div>
                </section>

                <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xl sm:p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Upcoming experiences</h3>
                            <p class="text-sm text-gray-500">Keep your delivery timeline sharp and share updates with your crew.</p>
                        </div>
                        <button type="button" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-semibold text-gray-600 hover:border-indigo-400 hover:text-indigo-600" @click="showPlanner = true">Add new</button>
                    </div>

                    <div class="mt-8 space-y-4">
                        <template x-for="event in upcoming" :key="event.title + event.date">
                            <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900" x-text="event.title"></p>
                                    <p class="text-xs text-gray-500" x-text="event.location"></p>
                                </div>
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-white px-3 py-1 font-medium text-indigo-600" x-text="event.status"></span>
                                    <span class="text-gray-500" x-text="event.date"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            </div>

            <div class="space-y-6 lg:col-span-4">
                <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900">Execution pipeline</h3>
                    <p class="mt-1 text-sm text-gray-500">Track each milestone to keep every stakeholder aligned.</p>

                    <div class="mt-6 space-y-5">
                        <template x-for="stage in pipelines" :key="stage.title">
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-gray-600">
                                    <span x-text="stage.title"></span>
                                    <span x-text="stage.percent + '%'" :class="stage.percent > 80 ? 'text-emerald-600' : stage.percent > 60 ? 'text-indigo-600' : 'text-amber-600'"></span>
                                </div>
                                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-gray-100">
                                <div class="h-full rounded-full" :class="stage.color" :style="'width:' + stage.percent + '%'"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

                <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Crew checklist</h3>
                        <span class="text-xs font-semibold text-indigo-600" x-text="tasks.filter(task => task.done).length + '/' + tasks.length + ' done'"></span>
                    </div>
                    <div class="mt-5 space-y-4">
                        <template x-for="task in tasks" :key="task.title">
                            <button type="button" @click="task.done = !task.done" class="w-full text-left">
                                <div class="flex items-start gap-3 rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3 transition hover:border-indigo-300 hover:bg-white" :class="task.done ? 'border-emerald-200 bg-emerald-50' : ''">
                                    <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full border" :class="task.done ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-gray-300 text-gray-400'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold" :class="task.done ? 'text-emerald-700 line-through' : 'text-gray-900'" x-text="task.title"></p>
                                        <p class="text-xs text-gray-500" x-text="task.owner + ' • ' + task.due"></p>
                                    </div>
                                </div>
                            </button>
                        </template>
                    </div>
                </section>

                <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900">Team focus</h3>
                    <p class="mt-1 text-sm text-gray-500">Snapshot of each area and how close it is to completion.</p>

                    <div class="mt-6 space-y-5">
                        <div class="flex items-center justify-between text-sm font-medium text-gray-700">
                            <span>Logistics alignment</span>
                            <span class="text-emerald-600">On track</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-emerald-500" style="width: 86%"></div>
                        </div>

                        <div class="flex items-center justify-between text-sm font-medium text-gray-700">
                            <span>Marketing cadence</span>
                            <span class="text-amber-600">Needs boost</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-amber-500" style="width: 62%"></div>
                        </div>

                        <div class="flex items-center justify-between text-sm font-medium text-gray-700">
                            <span>Partner deliverables</span>
                            <span class="text-indigo-600">Steady</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-indigo-500" style="width: 74%"></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div x-cloak x-show="showPlanner" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-40 px-4 py-10">
            <div class="w-full max-w-lg rounded-3xl border border-gray-200 bg-white p-8 shadow-2xl">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Add a quick session</h3>
                        <p class="text-sm text-gray-500">Add the essentials now—fine tune the rest later.</p>
                    </div>
                    <button type="button" class="text-sm font-semibold text-gray-400 hover:text-gray-600" @click="showPlanner = false">Close</button>
                </div>

                <form class="mt-6 space-y-5" @submit.prevent="upcoming.unshift({ title: newSession.title || 'Untitled experience', date: newSession.date || 'TBD', location: newSession.location || 'To be announced', status: 'Draft' }); newSession = { title: '', date: '', location: '' }; showPlanner = false;">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-500">Session name</label>
                        <input type="text" x-model="newSession.title" placeholder="VIP welcome reception" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-500">Date</label>
                            <input type="text" x-model="newSession.date" placeholder="Nov 21" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-500">Location</label>
                            <input type="text" x-model="newSession.location" placeholder="Atrium Hall" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">Tip: you can assign owners and reminders once the session is created.</p>
                        <div class="flex gap-3">
                            <button type="button" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-semibold text-gray-600 hover:border-gray-300" @click="showPlanner = false">Cancel</button>
                            <button type="submit" class="rounded-full bg-indigo-600 px-5 py-2 text-xs font-semibold uppercase tracking-wide text-white hover:bg-indigo-500">Add session</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
