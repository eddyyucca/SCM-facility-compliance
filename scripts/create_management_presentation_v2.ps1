$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$outputDir = Join-Path $projectRoot 'docs'
if (-not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir | Out-Null
}

$outputPath = Join-Path $outputDir 'Presentasi-Manfaat-App-GA-v2.pptx'
$logoPath = Join-Path $projectRoot 'public\icons\GA-SCM.png'

$COLOR_NAVY = 0x17365D
$COLOR_TEAL = 0x1C8C7C
$COLOR_GOLD = 0xD9A441
$COLOR_SOFT = 0xF4F7FB
$COLOR_DARK = 0x22313F
$COLOR_MUTED = 0x6C7A89
$COLOR_LINE = 0xDDE5EF

function Set-TextStyle {
    param(
        $TextRange,
        [int]$Size = 18,
        [string]$Font = 'Aptos',
        [int]$Color = 0x22313F,
        [bool]$Bold = $false
    )

    try { $TextRange.Font.Name = $Font } catch {}
    try { $TextRange.Font.NameAscii = $Font } catch {}
    try { $TextRange.Font.Size = $Size } catch {}
    try { $TextRange.Font.Bold = [int]$Bold } catch {}
    try { $TextRange.Font.Color.RGB = $Color } catch {}
}

function Add-RoundedCard {
    param($Slide, [double]$Left, [double]$Top, [double]$Width, [double]$Height, [int]$FillColor = 0xFFFFFF, [int]$LineColor = 0xDDE5EF)
    $shape = $Slide.Shapes.AddShape(5, $Left, $Top, $Width, $Height)
    $shape.Fill.ForeColor.RGB = $FillColor
    $shape.Line.ForeColor.RGB = $LineColor
    return $shape
}

function Add-HeaderBand {
    param($Slide, [string]$Title, [string]$Subtitle = '')
    $band = $Slide.Shapes.AddShape(1, 0, 0, 960, 92)
    $band.Fill.ForeColor.RGB = $COLOR_NAVY
    $band.Line.Visible = 0

    $accent = $Slide.Shapes.AddShape(1, 0, 92, 260, 6)
    $accent.Fill.ForeColor.RGB = $COLOR_TEAL
    $accent.Line.Visible = 0

    $titleBox = $Slide.Shapes.AddTextbox(1, 42, 18, 640, 32)
    $titleBox.TextFrame.TextRange.Text = $Title
    Set-TextStyle -TextRange $titleBox.TextFrame.TextRange -Size 26 -Font 'Aptos Display' -Color 0xFFFFFF -Bold $true

    if ($Subtitle) {
        $subBox = $Slide.Shapes.AddTextbox(1, 44, 50, 700, 22)
        $subBox.TextFrame.TextRange.Text = $Subtitle
        Set-TextStyle -TextRange $subBox.TextFrame.TextRange -Size 11 -Color 0xD9E7F5
    }

    if (Test-Path $logoPath) {
        $Slide.Shapes.AddPicture($logoPath, $false, $true, 810, 14, 118, 118) | Out-Null
    }
}

function Add-TitleSlide {
    param($Presentation)
    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 12)
    $slide.Background.Fill.ForeColor.RGB = $COLOR_SOFT

    $bg1 = $slide.Shapes.AddShape(1, 0, 0, 960, 200)
    $bg1.Fill.ForeColor.RGB = $COLOR_NAVY
    $bg1.Line.Visible = 0

    $bg2 = $slide.Shapes.AddShape(1, 0, 200, 960, 340)
    $bg2.Fill.ForeColor.RGB = 0xF7F9FC
    $bg2.Line.Visible = 0

    $blob1 = $slide.Shapes.AddShape(9, 650, -50, 250, 250)
    $blob1.Fill.ForeColor.RGB = 0x254F7A
    $blob1.Line.Visible = 0
    $blob1.Rotation = 22

    $blob2 = $slide.Shapes.AddShape(9, 720, 250, 190, 190)
    $blob2.Fill.ForeColor.RGB = 0xD9A441
    $blob2.Line.Visible = 0

    $title = $slide.Shapes.AddTextbox(1, 56, 86, 550, 86)
    $title.TextFrame.TextRange.Text = "Transformasi Monitoring Layanan GA"
    Set-TextStyle -TextRange $title.TextFrame.TextRange -Size 29 -Font 'Aptos Display' -Color 0xFFFFFF -Bold $true

    $subtitle = $slide.Shapes.AddTextbox(1, 58, 215, 560, 90)
    $subtitle.TextFrame.TextRange.Text = "SCM GA Complaint Management App untuk menutup lost report, memperkuat monitoring, dan membangun service excellence berbasis data."
    Set-TextStyle -TextRange $subtitle.TextFrame.TextRange -Size 18 -Color $COLOR_DARK

    $tag1 = Add-RoundedCard -Slide $slide -Left 58 -Top 334 -Width 210 -Height 72 -FillColor 0xFFFFFF -LineColor $COLOR_LINE
    $tag2 = Add-RoundedCard -Slide $slide -Left 286 -Top 334 -Width 210 -Height 72 -FillColor 0xFFFFFF -LineColor $COLOR_LINE
    $tag3 = Add-RoundedCard -Slide $slide -Left 514 -Top 334 -Width 210 -Height 72 -FillColor 0xFFFFFF -LineColor $COLOR_LINE

    $t1 = $slide.Shapes.AddTextbox(1, 78, 350, 170, 42)
    $t1.TextFrame.TextRange.Text = "Satu pintu laporan`nlebih tertib"
    Set-TextStyle -TextRange $t1.TextFrame.TextRange -Size 15 -Color $COLOR_DARK -Bold $true

    $t2 = $slide.Shapes.AddTextbox(1, 306, 350, 170, 42)
    $t2.TextFrame.TextRange.Text = "Monitoring owner`nlebih penuh"
    Set-TextStyle -TextRange $t2.TextFrame.TextRange -Size 15 -Color $COLOR_DARK -Bold $true

    $t3 = $slide.Shapes.AddTextbox(1, 534, 350, 170, 42)
    $t3.TextFrame.TextRange.Text = "Budaya feedback`ndan reward"
    Set-TextStyle -TextRange $t3.TextFrame.TextRange -Size 15 -Color $COLOR_DARK -Bold $true
}

function Add-ProblemSlide {
    param($Presentation)
    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 12)
    $slide.Background.Fill.ForeColor.RGB = 0xFFFFFF
    Add-HeaderBand -Slide $slide -Title 'Masalah Saat Ini' -Subtitle 'Kondisi operasional yang membuat kualitas service sulit dipantau'

    $left = Add-RoundedCard -Slide $slide -Left 48 -Top 128 -Width 388 -Height 340 -FillColor 0xF9FBFD -LineColor $COLOR_LINE
    $right = Add-RoundedCard -Slide $slide -Left 462 -Top 128 -Width 448 -Height 340 -FillColor 0x17365D -LineColor 0x17365D

    $leftTitle = $slide.Shapes.AddTextbox(1, 68, 146, 300, 24)
    $leftTitle.TextFrame.TextRange.Text = 'Pain Point Lapangan'
    Set-TextStyle -TextRange $leftTitle.TextFrame.TextRange -Size 20 -Font 'Aptos Display' -Color $COLOR_NAVY -Bold $true

    $leftBody = $slide.Shapes.AddTextbox(1, 68, 186, 330, 250)
    $leftBody.TextFrame.TextRange.Text = "• Banyak laporan masuk ke Aden tetapi respon belum termonitor.`r• Sebagian laporan berpotensi hilang karena tersebar di chat/pesan personal.`r• Owner belum punya satu dashboard untuk melihat status layanan.`r• Sulit mengukur mana area bermasalah dan mana tim yang perlu support."
    Set-TextStyle -TextRange $leftBody.TextFrame.TextRange -Size 18 -Color $COLOR_DARK

    $rightTitle = $slide.Shapes.AddTextbox(1, 492, 148, 300, 24)
    $rightTitle.TextFrame.TextRange.Text = 'Akibat ke General Affairs'
    Set-TextStyle -TextRange $rightTitle.TextFrame.TextRange -Size 20 -Font 'Aptos Display' -Color 0xFFFFFF -Bold $true

    $stats = @(
        @{ Top = 194; Value = 'Lost report'; Desc = 'Keluhan bisa tidak tertindaklanjuti dan menurunkan kepercayaan user.' },
        @{ Top = 268; Value = 'No monitoring'; Desc = 'Management sulit melihat progress, backlog, dan overdue.' },
        @{ Top = 342; Value = 'No evidence'; Desc = 'Kinerja service sulit dibuktikan karena datanya tidak utuh.' }
    )

    foreach ($item in $stats) {
        $box = Add-RoundedCard -Slide $slide -Left 492 -Top $item.Top -Width 390 -Height 58 -FillColor 0x224A75 -LineColor 0x224A75
        $txt = $slide.Shapes.AddTextbox(1, 510, $item.Top + 10, 120, 18)
        $txt.TextFrame.TextRange.Text = $item.Value
        Set-TextStyle -TextRange $txt.TextFrame.TextRange -Size 16 -Font 'Aptos Display' -Color 0xFFD77A -Bold $true

        $desc = $slide.Shapes.AddTextbox(1, 620, $item.Top + 8, 240, 34)
        $desc.TextFrame.TextRange.Text = $item.Desc
        Set-TextStyle -TextRange $desc.TextFrame.TextRange -Size 12 -Color 0xFFFFFF
    }
}

function Add-FishboneSlide {
    param($Presentation)
    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 12)
    $slide.Background.Fill.ForeColor.RGB = 0xF7F9FC
    Add-HeaderBand -Slide $slide -Title 'Fishbone Analysis' -Subtitle 'Akar masalah mengapa laporan service sering tidak terkontrol'

    $effect = Add-RoundedCard -Slide $slide -Left 760 -Top 225 -Width 150 -Height 74 -FillColor 0xD9534F -LineColor 0xD9534F
    $effectText = $slide.Shapes.AddTextbox(1, 776, 242, 118, 34)
    $effectText.TextFrame.TextRange.Text = "Lost Report &`nNo Monitoring"
    Set-TextStyle -TextRange $effectText.TextFrame.TextRange -Size 16 -Font 'Aptos Display' -Color 0xFFFFFF -Bold $true

    $spine = $slide.Shapes.AddLine(190, 262, 760, 262)
    $spine.Line.ForeColor.RGB = $COLOR_NAVY
    $spine.Line.Weight = 2.5

    $bones = @(
        @{ X1 = 270; Y1 = 262; X2 = 210; Y2 = 180; Title = 'People'; Body = 'Laporan bergantung pada orang tertentu, follow-up tidak seragam.'; Align = 'top' },
        @{ X1 = 420; Y1 = 262; X2 = 360; Y2 = 180; Title = 'Process'; Body = 'Belum ada alur tiket, status, dan ownership yang jelas.'; Align = 'top' },
        @{ X1 = 570; Y1 = 262; X2 = 510; Y2 = 180; Title = 'Tools'; Body = 'Masih memakai chat/pesan yang sulit ditelusuri ulang.'; Align = 'top' },
        @{ X1 = 330; Y1 = 262; X2 = 270; Y2 = 344; Title = 'Control'; Body = 'Owner tidak punya dashboard untuk melihat backlog dan overdue.'; Align = 'bottom' },
        @{ X1 = 500; Y1 = 262; X2 = 440; Y2 = 344; Title = 'Data'; Body = 'Tidak ada histori yang rapi untuk evaluasi dan reward.'; Align = 'bottom' },
        @{ X1 = 650; Y1 = 262; X2 = 590; Y2 = 344; Title = 'Culture'; Body = 'Belum terbentuk kebiasaan feedback dan pelaporan partisipatif.'; Align = 'bottom' }
    )

    foreach ($bone in $bones) {
        $line = $slide.Shapes.AddLine($bone.X1, $bone.Y1, $bone.X2, $bone.Y2)
        $line.Line.ForeColor.RGB = $COLOR_NAVY
        $line.Line.Weight = 2

        if ($bone.Align -eq 'top') {
            $cardTop = $bone.Y2 - 78
        } else {
            $cardTop = $bone.Y2 + 10
        }

        $card = Add-RoundedCard -Slide $slide -Left ($bone.X2 - 52) -Top $cardTop -Width 175 -Height 74 -FillColor 0xFFFFFF -LineColor $COLOR_LINE
        $title = $slide.Shapes.AddTextbox(1, $bone.X2 - 38, $cardTop + 8, 140, 18)
        $title.TextFrame.TextRange.Text = $bone.Title
        Set-TextStyle -TextRange $title.TextFrame.TextRange -Size 14 -Font 'Aptos Display' -Color $COLOR_TEAL -Bold $true

        $body = $slide.Shapes.AddTextbox(1, $bone.X2 - 38, $cardTop + 26, 144, 38)
        $body.TextFrame.TextRange.Text = $bone.Body
        Set-TextStyle -TextRange $body.TextFrame.TextRange -Size 10 -Color $COLOR_DARK
    }
}

function Add-StageSlide {
    param($Presentation)
    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 12)
    $slide.Background.Fill.ForeColor.RGB = 0xFFFFFF
    Add-HeaderBand -Slide $slide -Title 'Dua Tahap Implementasi' -Subtitle 'Membangun disiplin pelaporan lalu monitoring penuh'

    $left = Add-RoundedCard -Slide $slide -Left 55 -Top 136 -Width 388 -Height 310 -FillColor 0xF8FBFF -LineColor $COLOR_LINE
    $right = Add-RoundedCard -Slide $slide -Left 473 -Top 136 -Width 388 -Height 310 -FillColor 0xFDF9F1 -LineColor $COLOR_LINE

    $num1 = $slide.Shapes.AddShape(9, 72, 120, 46, 46)
    $num1.Fill.ForeColor.RGB = $COLOR_TEAL
    $num1.Line.Visible = 0
    $num1Text = $slide.Shapes.AddTextbox(1, 84, 131, 24, 24)
    $num1Text.TextFrame.TextRange.Text = '1'
    Set-TextStyle -TextRange $num1Text.TextFrame.TextRange -Size 18 -Color 0xFFFFFF -Bold $true

    $num2 = $slide.Shapes.AddShape(9, 490, 120, 46, 46)
    $num2.Fill.ForeColor.RGB = $COLOR_GOLD
    $num2.Line.Visible = 0
    $num2Text = $slide.Shapes.AddTextbox(1, 502, 131, 24, 24)
    $num2Text.TextFrame.TextRange.Text = '2'
    Set-TextStyle -TextRange $num2Text.TextRange -Size 18 -Color 0xFFFFFF -Bold $true

    $title1 = $slide.Shapes.AddTextbox(1, 130, 142, 260, 20)
    $title1.TextFrame.TextRange.Text = 'Proses Aplikasi untuk User Melapor'
    Set-TextStyle -TextRange $title1.TextFrame.TextRange -Size 20 -Font 'Aptos Display' -Color $COLOR_NAVY -Bold $true

    $body1 = $slide.Shapes.AddTextbox(1, 76, 188, 320, 220)
    $body1.TextFrame.TextRange.Text = "• User melapor dari HP/desktop.`r• Pilih tipe layanan, lokasi, deskripsi, dan foto.`r• Sistem membuat nomor tiket otomatis.`r• Laporan lebih rapi dan tidak mudah hilang.`r• User bisa tracking status secara jelas."
    Set-TextStyle -TextRange $body1.TextFrame.TextRange -Size 17 -Color $COLOR_DARK

    $title2 = $slide.Shapes.AddTextbox(1, 548, 142, 260, 20)
    $title2.TextFrame.TextRange.Text = 'Dashboard 4 Sub User + Owner'
    Set-TextStyle -TextRange $title2.TextFrame.TextRange -Size 20 -Font 'Aptos Display' -Color $COLOR_NAVY -Bold $true

    $body2 = $slide.Shapes.AddTextbox(1, 494, 188, 320, 220)
    $body2.TextFrame.TextRange.Text = "• Superadmin, Receptionist, HK, dan Laundry.`r• Owner memantau semua status secara penuh.`r• Ada view open, progress, closed, rejected, dan overdue.`r• Management dapat melihat performa per tipe layanan dan tren laporan."
    Set-TextStyle -TextRange $body2.TextFrame.TextRange -Size 17 -Color $COLOR_DARK
}

function Add-ParticipationSlide {
    param($Presentation)
    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 12)
    $slide.Background.Fill.ForeColor.RGB = 0xF7F9FC
    Add-HeaderBand -Slide $slide -Title 'Top Pelapor Menjadi Agen Service' -Subtitle 'Budaya pelaporan aktif mendorong perbaikan layanan lebih cepat'

    $centerCard = Add-RoundedCard -Slide $slide -Left 302 -Top 170 -Width 350 -Height 130 -FillColor 0xFFFFFF -LineColor $COLOR_LINE
    $centerTitle = $slide.Shapes.AddTextbox(1, 340, 192, 280, 24)
    $centerTitle.TextFrame.TextRange.Text = 'Top Pelapor = Partisipasi Positif'
    Set-TextStyle -TextRange $centerTitle.TextFrame.TextRange -Size 21 -Font 'Aptos Display' -Color $COLOR_TEAL -Bold $true
    $centerBody = $slide.Shapes.AddTextbox(1, 328, 225, 300, 52)
    $centerBody.TextFrame.TextRange.Text = 'Semua orang bisa ikut menjaga kualitas area kerja dengan melaporkan masalah service secara cepat dan terstruktur.'
    Set-TextStyle -TextRange $centerBody.TextFrame.TextRange -Size 15 -Color $COLOR_DARK

    $nodes = @(
        @{ Left = 78; Top = 144; Title = 'Deteksi lebih cepat'; Body = 'Masalah operasional terlihat lebih dini sebelum membesar.'; Color = 0xEAF6F3 },
        @{ Left = 88; Top = 332; Title = 'Data reward'; Body = 'Pelapor aktif dapat diberi apresiasi berbasis data nyata.'; Color = 0xFFF5E7 },
        @{ Left = 680; Top = 144; Title = 'Feedback service'; Body = 'Tim service punya input objektif untuk evaluasi dan perbaikan.'; Color = 0xEEF4FC },
        @{ Left = 676; Top = 332; Title = 'Budaya ownership'; Body = 'User merasa ikut memiliki kualitas lingkungan kerja.'; Color = 0xF6EEF9 }
    )

    foreach ($node in $nodes) {
        $card = Add-RoundedCard -Slide $slide -Left $node.Left -Top $node.Top -Width 205 -Height 96 -FillColor $node.Color -LineColor $COLOR_LINE
        $t = $slide.Shapes.AddTextbox(1, $node.Left + 14, $node.Top + 12, 176, 18)
        $t.TextFrame.TextRange.Text = $node.Title
        Set-TextStyle -TextRange $t.TextFrame.TextRange -Size 15 -Font 'Aptos Display' -Color $COLOR_NAVY -Bold $true
        $b = $slide.Shapes.AddTextbox(1, $node.Left + 14, $node.Top + 34, 176, 42)
        $b.TextFrame.TextRange.Text = $node.Body
        Set-TextStyle -TextRange $b.TextFrame.TextRange -Size 11 -Color $COLOR_DARK
    }

    $slide.Shapes.AddLine(283, 194, 302, 220).Line.ForeColor.RGB = $COLOR_MUTED
    $slide.Shapes.AddLine(283, 380, 302, 252).Line.ForeColor.RGB = $COLOR_MUTED
    $slide.Shapes.AddLine(652, 220, 680, 194).Line.ForeColor.RGB = $COLOR_MUTED
    $slide.Shapes.AddLine(652, 252, 680, 380).Line.ForeColor.RGB = $COLOR_MUTED
}

function Add-PDCASlide {
    param($Presentation)
    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 12)
    $slide.Background.Fill.ForeColor.RGB = 0xFFFFFF
    Add-HeaderBand -Slide $slide -Title 'Tahapan PDCA' -Subtitle 'Kerangka improvement berkelanjutan setelah aplikasi dijalankan'

    $centerX = 478
    $centerY = 275

    $plan = $slide.Shapes.AddShape(9, 372, 122, 120, 78)
    $plan.Fill.ForeColor.RGB = 0x1C8C7C
    $plan.Line.Visible = 0
    $do = $slide.Shapes.AddShape(9, 596, 216, 120, 78)
    $do.Fill.ForeColor.RGB = 0x2F6FA5
    $do.Line.Visible = 0
    $check = $slide.Shapes.AddShape(9, 372, 350, 120, 78)
    $check.Fill.ForeColor.RGB = 0xD9A441
    $check.Line.Visible = 0
    $act = $slide.Shapes.AddShape(9, 150, 216, 120, 78)
    $act.Fill.ForeColor.RGB = 0xB5576B
    $act.Line.Visible = 0

    $boxes = @(
        @{ Left = 392; Top = 145; Title = 'PLAN'; Body = 'Tetapkan target respon, alur tiket, SLA, dan PIC.'; Color = 0xFFFFFF },
        @{ Left = 616; Top = 239; Title = 'DO'; Body = 'Jalankan aplikasi, biasakan user melapor, dan operasionalkan dashboard.'; Color = 0xFFFFFF },
        @{ Left = 392; Top = 373; Title = 'CHECK'; Body = 'Pantau open, progress, closed, overdue, top pelapor, dan feedback.'; Color = 0xFFFFFF },
        @{ Left = 170; Top = 239; Title = 'ACT'; Body = 'Perbaiki proses, beri reward, dan tingkatkan standar layanan.'; Color = 0xFFFFFF }
    )

    foreach ($box in $boxes) {
        $tb = $slide.Shapes.AddTextbox(1, $box.Left, $box.Top, 82, 18)
        $tb.TextFrame.TextRange.Text = $box.Title
        Set-TextStyle -TextRange $tb.TextFrame.TextRange -Size 18 -Font 'Aptos Display' -Color $box.Color -Bold $true

        $bd = $slide.Shapes.AddTextbox(1, $box.Left - 2, $box.Top + 22, 105, 34)
        $bd.TextFrame.TextRange.Text = $box.Body
        Set-TextStyle -TextRange $bd.TextFrame.TextRange -Size 10 -Color $box.Color
    }

    $circle = $slide.Shapes.AddShape(9, 405, 223, 145, 95)
    $circle.Fill.ForeColor.RGB = 0xF4F7FB
    $circle.Line.ForeColor.RGB = $COLOR_LINE
    $core = $slide.Shapes.AddTextbox(1, 430, 246, 98, 46)
    $core.TextFrame.TextRange.Text = "Continuous`nImprovement"
    Set-TextStyle -TextRange $core.TextFrame.TextRange -Size 16 -Font 'Aptos Display' -Color $COLOR_NAVY -Bold $true

    $a1 = $slide.Shapes.AddLine(490, 180, 620, 238)
    $a2 = $slide.Shapes.AddLine(655, 294, 492, 390)
    $a3 = $slide.Shapes.AddLine(372, 390, 245, 294)
    $a4 = $slide.Shapes.AddLine(238, 216, 372, 180)
    foreach ($a in @($a1, $a2, $a3, $a4)) {
        $a.Line.ForeColor.RGB = $COLOR_MUTED
        $a.Line.Weight = 2
        $a.Line.EndArrowheadStyle = 3
    }
}

function Add-ImpactSlide {
    param($Presentation)
    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 12)
    $slide.Background.Fill.ForeColor.RGB = 0xF7F9FC
    Add-HeaderBand -Slide $slide -Title 'Perhitungan Dampak untuk GA' -Subtitle 'Simulasi sederhana jika sistem berjalan lancar dan disiplin dipakai'

    $cards = @(
        @{ Left = 52; Value = '115'; Label = 'Volume laporan'; Note = 'Laporan aktual yang menjadi dasar simulasi.'; Fill = 0xFFFFFF },
        @{ Left = 272; Value = '17'; Label = 'Laporan terselamatkan'; Note = 'Jika lost report turun dari 20% menjadi 5%.'; Fill = 0xFFFFFF },
        @{ Left = 492; Value = '100%'; Label = 'Monitoring owner'; Note = 'Semua tiket terlihat dalam satu dashboard.'; Fill = 0xFFFFFF },
        @{ Left = 712; Value = '+30%'; Label = 'Potensi respon'; Note = 'Karena status dan prioritas lebih mudah dipantau.'; Fill = 0xFFFFFF }
    )

    foreach ($card in $cards) {
        $shape = Add-RoundedCard -Slide $slide -Left $card.Left -Top 150 -Width 190 -Height 160 -FillColor $card.Fill -LineColor $COLOR_LINE
        $v = $slide.Shapes.AddTextbox(1, $card.Left + 18, 176, 150, 34)
        $v.TextFrame.TextRange.Text = $card.Value
        Set-TextStyle -TextRange $v.TextFrame.TextRange -Size 28 -Font 'Aptos Display' -Color $COLOR_TEAL -Bold $true
        $l = $slide.Shapes.AddTextbox(1, $card.Left + 18, 220, 150, 20)
        $l.TextFrame.TextRange.Text = $card.Label
        Set-TextStyle -TextRange $l.TextFrame.TextRange -Size 15 -Font 'Aptos Display' -Color $COLOR_NAVY -Bold $true
        $n = $slide.Shapes.AddTextbox(1, $card.Left + 18, 252, 154, 38)
        $n.TextFrame.TextRange.Text = $card.Note
        Set-TextStyle -TextRange $n.TextFrame.TextRange -Size 11 -Color $COLOR_DARK
    }

    $impactBox = Add-RoundedCard -Slide $slide -Left 86 -Top 345 -Width 790 -Height 96 -FillColor 0x17365D -LineColor 0x17365D
    $impactText = $slide.Shapes.AddTextbox(1, 116, 370, 730, 46)
    $impactText.TextFrame.TextRange.Text = "Jika aplikasi dijalankan konsisten, GA akan terlihat lebih responsif, lebih tertib, dan lebih dipercaya karena ada bukti kerja yang terukur untuk monitoring, evaluasi, dan service improvement."
    Set-TextStyle -TextRange $impactText.TextFrame.TextRange -Size 18 -Color 0xFFFFFF
}

function Add-ClosingSlide {
    param($Presentation)
    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 12)
    $slide.Background.Fill.ForeColor.RGB = $COLOR_NAVY

    $line = $slide.Shapes.AddShape(1, 60, 100, 170, 7)
    $line.Fill.ForeColor.RGB = $COLOR_GOLD
    $line.Line.Visible = 0

    $title = $slide.Shapes.AddTextbox(1, 60, 132, 650, 70)
    $title.TextFrame.TextRange.Text = "Kesimpulan untuk Management"
    Set-TextStyle -TextRange $title.TextFrame.TextRange -Size 30 -Font 'Aptos Display' -Color 0xFFFFFF -Bold $true

    $body = $slide.Shapes.AddTextbox(1, 62, 218, 600, 180)
    $body.TextFrame.TextRange.Text = "• Aplikasi ini menutup celah lost report dan no monitoring.`r• Tahap 1 membuat user mudah melapor dengan tiket yang jelas.`r• Tahap 2 memberi kontrol penuh untuk sub user operasional dan owner.`r• Fishbone menunjukkan akar masalah utama ada pada process, control, dan data.`r• Dengan PDCA, aplikasi ini dapat menjadi fondasi continuous improvement layanan GA."
    Set-TextStyle -TextRange $body.TextFrame.TextRange -Size 19 -Color 0xEAF2FB

    if (Test-Path $logoPath) {
        $slide.Shapes.AddPicture($logoPath, $false, $true, 735, 120, 145, 145) | Out-Null
    }

    $tag = Add-RoundedCard -Slide $slide -Left 640 -Top 320 -Width 240 -Height 86 -FillColor 0x224A75 -LineColor 0x224A75
    $tagText = $slide.Shapes.AddTextbox(1, 662, 344, 196, 34)
    $tagText.TextFrame.TextRange.Text = "Service lebih cepat.`nData lebih kuat."
    Set-TextStyle -TextRange $tagText.TextFrame.TextRange -Size 18 -Font 'Aptos Display' -Color 0xFFD77A -Bold $true
}

$powerPoint = $null
$presentation = $null

try {
    $powerPoint = New-Object -ComObject PowerPoint.Application
    $powerPoint.Visible = -1
    $presentation = $powerPoint.Presentations.Add()
    $presentation.PageSetup.SlideSize = 16

    Add-TitleSlide -Presentation $presentation
    Add-ProblemSlide -Presentation $presentation
    Add-FishboneSlide -Presentation $presentation
    Add-StageSlide -Presentation $presentation
    Add-ParticipationSlide -Presentation $presentation
    Add-PDCASlide -Presentation $presentation
    Add-ImpactSlide -Presentation $presentation
    Add-ClosingSlide -Presentation $presentation

    if (Test-Path $outputPath) {
        Remove-Item -LiteralPath $outputPath -Force
    }

    $presentation.SaveAs($outputPath)
    $presentation.Close()
    $powerPoint.Quit()
    Write-Output "Presentation created: $outputPath"
}
finally {
    if ($presentation -ne $null) {
        try { $presentation.Close() } catch {}
    }
    if ($powerPoint -ne $null) {
        try { $powerPoint.Quit() } catch {}
    }
}
