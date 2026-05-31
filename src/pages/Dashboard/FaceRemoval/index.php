<?php
/**
 * Face Removal — dedicated dashboard page.
 *
 * This page is the standalone landing for the face-removal feature. It
 * gives users a clear, separate place to:
 *   - upload / change their face image
 *   - see real-time PimEyes opt-out status (kind=4 step)
 *   - understand what we do, what's covered, and what's planned
 *
 * Reached via:
 *   /new_dashboard/face   (user-facing URL; routed by NewDashboard/index.php JS)
 *   /dashboard/content/face   (XHR endpoint loaded into #content)
 *
 * The hero card is rendered by databrokers_face.php (status + image
 * upload prompt). Everything else on this page is supporting content
 * specific to making the feature feel like a real product surface
 * rather than a single tucked-away card.
 */
?>
<div class="max-w-[1200px] mx-auto">

    <!-- Page header: sets context that this is a distinct service area. -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-[12px]">
        <div>
            <div class="text-[12px] sm:text-[13px] font-semibold uppercase tracking-[0.12em] text-[#5B5F66]">
                Face Removal
            </div>
            <h1 class="mt-[4px] font-bold text-[24px] sm:text-[30px] md:text-[34px] text-[#010205] leading-[1.15]">
                Remove your face from search engines
            </h1>
            <p class="mt-[6px] text-[13px] sm:text-[14px] text-[#5B5F66] max-w-[680px]">
                Face-search engines like PimEyes index photos of your face from across the public web.
                We submit official opt-out requests on your behalf and monitor for new matches.
            </p>
        </div>
    </div>

    <!-- Main status card -->
    <div id="face-removal-status" class="mt-[24px] rounded-[24px] bg-white border border-[#F1F1F1] p-[24px] sm:p-[28px]">
        <?php require_once BASEPATH . '/src/pages/Dashboard/Main/databrokers/databrokers_face.php'; ?>
    </div>

    <!-- Coverage card: lists which face-search services we currently
         handle. Today only PimEyes; designed to grow as more brokers
         are added in sites/. The per-row state could later read from
         results table by target_domain. -->
    <div class="mt-[16px] rounded-[24px] bg-white border border-[#F1F1F1] p-[24px] sm:p-[28px]">
        <div class="flex items-center justify-between mb-[14px]">
            <h3 class="text-[16px] font-bold text-[#010205]">Face-search engines we cover</h3>
            <span class="text-[12px] text-[#878C91]">
                <span class="font-semibold text-[#010205]">1</span> active
            </span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[10px]">
            <div class="flex items-center gap-[12px] rounded-[12px] border border-[#EAECEF] bg-white px-[12px] py-[10px]">
                <div class="shrink-0 w-[36px] h-[36px] rounded-[8px] bg-[#EAF5ED] flex items-center justify-center text-[#1A7F40] font-bold">
                    P
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[13px] font-semibold text-[#010205] truncate">PimEyes</div>
                    <div class="text-[11px] text-[#878C91]">pimeyes.com</div>
                </div>
                <span class="text-[10px] font-semibold uppercase tracking-[0.1em] text-[#1A7F40] bg-[#E8F7EF] px-[8px] py-[3px] rounded-full">Active</span>
            </div>
            <div class="flex items-center gap-[12px] rounded-[12px] border border-dashed border-[#EAECEF] bg-[#FAFAFA] px-[12px] py-[10px] opacity-70">
                <div class="shrink-0 w-[36px] h-[36px] rounded-[8px] bg-[#F1F2F4] flex items-center justify-center text-[#878C91] font-bold">
                    F
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[13px] font-semibold text-[#5B5F66] truncate">FaceCheck.ID</div>
                    <div class="text-[11px] text-[#878C91]">facecheck.id</div>
                </div>
                <span class="text-[10px] font-semibold uppercase tracking-[0.1em] text-[#878C91] bg-[#F1F2F4] px-[8px] py-[3px] rounded-full">Coming soon</span>
            </div>
            <div class="flex items-center gap-[12px] rounded-[12px] border border-dashed border-[#EAECEF] bg-[#FAFAFA] px-[12px] py-[10px] opacity-70">
                <div class="shrink-0 w-[36px] h-[36px] rounded-[8px] bg-[#F1F2F4] flex items-center justify-center text-[#878C91] font-bold">
                    L
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[13px] font-semibold text-[#5B5F66] truncate">Lenso.ai</div>
                    <div class="text-[11px] text-[#878C91]">lenso.ai</div>
                </div>
                <span class="text-[10px] font-semibold uppercase tracking-[0.1em] text-[#878C91] bg-[#F1F2F4] px-[8px] py-[3px] rounded-full">Coming soon</span>
            </div>
        </div>
    </div>

    <!-- How it works -->
    <div class="mt-[16px] rounded-[24px] bg-white border border-[#F1F1F1] p-[24px] sm:p-[28px]">
        <h3 class="text-[16px] font-bold text-[#010205] mb-[16px]">How it works</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-[14px]">
            <div class="rounded-[14px] border border-[#EAECEF] p-[14px]">
                <div class="w-[28px] h-[28px] rounded-full bg-[#E8F7EF] text-[#1A7F40] flex items-center justify-center text-[12px] font-bold mb-[10px]">1</div>
                <div class="text-[13px] font-semibold text-[#010205] mb-[4px]">Upload your face</div>
                <p class="text-[12px] text-[#5B5F66] leading-[1.5]">
                    A single clear photo of your face is enough. Used only for the opt-out submission, never shared.
                </p>
            </div>
            <div class="rounded-[14px] border border-[#EAECEF] p-[14px]">
                <div class="w-[28px] h-[28px] rounded-full bg-[#E8F7EF] text-[#1A7F40] flex items-center justify-center text-[12px] font-bold mb-[10px]">2</div>
                <div class="text-[13px] font-semibold text-[#010205] mb-[4px]">We submit opt-outs</div>
                <p class="text-[12px] text-[#5B5F66] leading-[1.5]">
                    Our pipeline files official removal requests with each covered face-search engine.
                </p>
            </div>
            <div class="rounded-[14px] border border-[#EAECEF] p-[14px]">
                <div class="w-[28px] h-[28px] rounded-full bg-[#E8F7EF] text-[#1A7F40] flex items-center justify-center text-[12px] font-bold mb-[10px]">3</div>
                <div class="text-[13px] font-semibold text-[#010205] mb-[4px]">Track + retry</div>
                <p class="text-[12px] text-[#5B5F66] leading-[1.5]">
                    Removal status updates here. Failed attempts are automatically retried on the next pipeline run.
                </p>
            </div>
        </div>
    </div>

    <!-- FAQ-ish block: pre-empts the most common support questions. -->
    <div class="mt-[16px] rounded-[24px] bg-white border border-[#F1F1F1] p-[24px] sm:p-[28px]">
        <h3 class="text-[16px] font-bold text-[#010205] mb-[12px]">Common questions</h3>
        <div class="space-y-[10px] text-[13px] leading-[1.55] text-[#374151]">
            <p>
                <strong class="text-[#010205]">How long does opt-out take?</strong><br>
                PimEyes processes opt-out requests in 24-72 hours. Other services vary. Once we submit, the status here moves to "Removed" but the actual removal completes asynchronously on the service's side.
            </p>
            <p>
                <strong class="text-[#010205]">What if my face appears under a different photo?</strong><br>
                Each opt-out covers all photos matched to the face in your uploaded image. If you have separate identities (e.g. before/after a major change in appearance), upload them one at a time.
            </p>
            <p>
                <strong class="text-[#010205]">Can I change the photo I uploaded?</strong><br>
                Yes — go to <a href="/new_dashboard/editinfo" data-link class="text-[#24A556] underline underline-offset-[2px]">Edit your info</a> and upload a new one. The system will automatically re-queue removal with the new image.
            </p>
        </div>
    </div>
</div>
