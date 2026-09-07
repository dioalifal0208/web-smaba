@extends('layouts.public')

@section('title', 'Beranda')
@section('description', 'Portal informasi resmi, berita, pengumuman, agenda, dan layanan digital SMA Negeri 1 Babat.')

@section('content')
    @php
        $announcements = collect($announcements ?? []);
        $services = collect($services ?? []);
        $news = collect($news ?? []);
        $agendas = collect($agendas ?? []);
    @endphp

    <x-home.hero />

    <section
        id="pengumuman"
        class="scroll-mt-24 bg-surface py-12 md:py-16"
        aria-labelledby="pengumuman-heading"
    >
        <x-ui.container>
            <x-ui.section-heading
                id="pengumuman-heading"
                title="Pengumuman Penting"
            />

            @if ($announcements->isNotEmpty())
                <div class="mt-6 divide-y divide-border border-y border-border">
                    @foreach ($announcements as $announcement)
                        <x-home.announcement-item
                            :title="data_get($announcement, 'title')"
                            :date="data_get($announcement, 'date', data_get($announcement, 'start_date'))"
                            :excerpt="data_get($announcement, 'excerpt', data_get($announcement, 'summary'))"
                            :url="data_get($announcement, 'url')"
                            :is-important="(bool) data_get($announcement, 'is_important', data_get($announcement, 'important', true))"
                        />
                    @endforeach
                </div>
            @else
                <x-ui.empty-state
                    title="Belum ada pengumuman penting."
                    message="Pengumuman resmi akan ditampilkan di bagian ini setelah dipublikasikan."
                    class="mt-6"
                />
            @endif
        </x-ui.container>
    </section>

    <section
        id="layanan-digital"
        class="scroll-mt-24 bg-background py-12 md:py-16"
        aria-labelledby="layanan-digital-heading"
    >
        <x-ui.container>
            <x-ui.section-heading
                id="layanan-digital-heading"
                title="Layanan Digital"
            />

            @if ($services->isNotEmpty())
                <div class="mt-6 grid gap-3 md:grid-cols-2">
                    @foreach ($services as $service)
                        @php
                            $serviceUrl = data_get($service, 'url');
                            $serviceHost = $serviceUrl ? parse_url($serviceUrl, PHP_URL_HOST) : null;
                            $isExternal = filled($serviceHost) && $serviceHost !== request()->getHost();
                        @endphp

                        <x-home.service-link
                            :name="data_get($service, 'name')"
                            :description="data_get($service, 'description')"
                            :url="$serviceUrl"
                            :status="data_get($service, 'status')"
                            :access-type="data_get($service, 'access_type')"
                            :is-external="$isExternal"
                        />
                    @endforeach
                </div>
            @else
                <x-ui.empty-state
                    title="Layanan digital belum tersedia."
                    message="Tautan layanan resmi sekolah akan tampil setelah data layanan dipublikasikan."
                    class="mt-6"
                />
            @endif
        </x-ui.container>
    </section>

    <section
        id="berita"
        class="scroll-mt-24 bg-surface py-12 md:py-16"
        aria-labelledby="berita-heading"
    >
        <x-ui.container>
            <x-ui.section-heading
                id="berita-heading"
                title="Berita Terbaru"
            />

            @if ($news->isNotEmpty())
                <x-home.news-editorial
                    :lead="$news->first()"
                    :stories="$news->skip(1)->values()"
                    class="mt-6"
                />
            @else
                <x-ui.empty-state
                    title="Belum ada berita terbaru."
                    message="Berita resmi sekolah akan tampil setelah artikel dipublikasikan."
                    class="mt-6"
                />
            @endif
        </x-ui.container>
    </section>

    <section
        id="agenda"
        class="scroll-mt-24 bg-surface-muted py-12 md:py-16"
        aria-labelledby="agenda-heading"
    >
        <x-ui.container>
            <x-ui.section-heading
                id="agenda-heading"
                title="Agenda Terdekat"
            />

            @if ($agendas->isNotEmpty())
                <div class="mt-6 divide-y divide-border border-y border-border bg-surface">
                    @foreach ($agendas as $agenda)
                        <x-home.agenda-row
                            :start-date="data_get($agenda, 'start_date')"
                            :end-date="data_get($agenda, 'end_date')"
                            :time="data_get($agenda, 'time')"
                            :location="data_get($agenda, 'location')"
                            :category="data_get($agenda, 'category')"
                            :title="data_get($agenda, 'title')"
                            :status="data_get($agenda, 'status')"
                            :url="data_get($agenda, 'url')"
                        />
                    @endforeach
                </div>
            @else
                <x-ui.empty-state
                    title="Belum ada agenda terdekat."
                    message="Agenda sekolah akan tampil setelah jadwal resmi dipublikasikan."
                    class="mt-6"
                />
            @endif
        </x-ui.container>
    </section>
@endsection
