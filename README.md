# lancelotaws
Pipeline from local computer to AWS cloud

## Introduction

Before diving into the full concept, let’s take a look at how this page is actually generated.  

All the text content is stored in an **Excel file**, which includes a column for tags and two columns for languages: *French* and *English*.

A **Python script** injects this data into a **MySQL database**, and a bit of **PHP code** reads the database and generates the proper HTML based on the tags.

There’s no HTML written manually — making the content easy to maintain, multilingual by design, and fully dynamic.

This mechanism represents the core architecture of the entire website.  

And the best part? I can send everything from my hard drive to the live server with a single click.  

Feel free to explore more below.

## Problematic

I have a collection of poems on my hard drive, and I want to publish them in several different formats:

- I’d like a convenient file format, such as **PDF**, to easily share them.
- I publish my books using **LaTeX**, so I want the poems to be easily convertible into LaTeX.
- It would be useful to generate an **EPUB** version if needed.
- Ideally, I’d like a clean and elegant **webpage** that gathers all my poems.

### The easy solution: Word

The easiest solution would be to use a **Microsoft Word** file. I can export it as **PDF**, **HTML**, and probably even **LaTeX**. With proper use of headings and formatting, it could provide a consistent and visually appealing result.

It wouldn’t be a bad choice — in fact, I’m pretty sure this is what *95% of poetry writers* are doing.  

Well, it turns out I belong to the other *5%*, because Word simply isn’t convenient for me at all.
#### Problem #1: Plain text files

I write in **plain text**, using the old version of **Windows Notepad**.  

When inspiration strikes, I need my writing tool to appear in one second, with one click — nothing more.

Once a poem is written, I just drop the text file in the right folder.  

It can stay there, untouched, waiting for years before someone reads it again.

#### Problem #2: Too many copies

In terms of workflow, I’d end up with several versions: the plain text file, the HTML version on the website, exported files, etc.  

If I make a correction — even just a comma — I have to apply it everywhere.

And if I forget even once, everything becomes inconsistent. I lose track of the “most correct” version.  

**Real nightmare.**

#### Problem #3: Complex poems

Most of my poems are quite simple. But some include varied line breaks, indents, quotes…  

I also write **songs**, so I need structures like *verse*, *chorus*, *bridge*, etc.

Using something like **Markdown** for that would be far too limited — and unnecessarily complicated.

#### Problem #4: Not fluid at all

Writing poems is nice. Coding little apps is nice too.  

But uploading via FTP, adding <p></p> manually to every line, and propagating corrections everywhere...  

That’s *annoying* and *time-consuming*.

#### Problem #5: Dirty, dirty Word

I have a kind of minimalist mindset. I like clean code — no useless lines. Clean CSS — no unnecessary classes.

If you’ve ever exported a document from **Word**, you know that “clean” is the last word you’d use to describe the result.

Of course, I could write a script to clean that up (actually, I did that for my screenplays — but that’s another story).  

But if you’re aiming for a clean workflow, **Word should probably be kept out of it**.

## The real solution

After considering all of this, I came up with a more tailored solution: creating my own **markup language** and building a **pipeline** from my local hard drive to an **AWS server**.

So yes — if I had to sum up the project in a more poetic or “tech-portfolio” way, it would go like this:

**I have created my own markup language to publish my poems on a website.**

With a single click, I inject them into a pipeline: from my computer → to **GitHub** → to an **S3 bucket** → to an **EC2 instance** → to a **MySQL** database, extracted by a **PHP website**.

A shorter pipeline might be technically possible, but since I also need to upload the entire website — not just the poems — this approach makes more sense.  

Besides, while the website beautifully showcases the *poetic* part, GitHub is the right place to highlight the *coding* side.

And speaking of poetry and code, let’s take a look at what my custom markup language actually looks like.

### Lyra, a mark-up language for poetry

There are several challenges in creating such a language.

#### Multilingual typography

The main one is **typography rules**. I mainly write in *French*, but sometimes in *English* — and their punctuation and typographic conventions are very different.

**LaTeX** handles this quite well, but **HTML** doesn’t.  

So my master text must remain as typographically neutral as possible.  

Then, the **conversion script** is in charge of flattening inconsistencies and applying the correct formatting according to the language.

Here’s a short example to illustrate the challenge:

Some would ask: “What is typography?” That’s a legit question—that I need to answer in French.  

Vous demandez donc: « qu’est-ce que la typographie ? » Je vous répondrais bien — mais plus tard.

You can clearly see the differences: “ ” vs. « »; thin spaces vs. no spaces; em-dashes vs. hyphens…  

It needs to be handled carefully and automatically.

#### LaTeX constraints

I love publishing with **LaTeX**, but LaTeX has its own preferences.  

For instance, it doesn’t like “—” and expects you to write “---” instead.

After years of using LaTeX, typing “--” and “---” has become second nature.  

Also, as mentioned earlier, LaTeX expects *English-style spacing* by default, and applies the correct rules depending on the language setting.

I wouldn’t want to upset Mister LaTeX — so I comply.

#### Songs & poetry

As you already know, I write poetry — fine.  

But I also write **songs**, which means I need formatting for *verses*, *choruses*, *bridges*, and more.

It’s not a big deal, but it’s something my markup language must be able to handle.

#### The language

Taking all these constraints into account, I came up with a custom markup language called **Lyra** — probably because I’m a bit too obsessed with Ancient Greek culture.

You can see the full implementation in the code panel, but here are the key transformations it applies:

text = text.replace('\u202f',' ').replace('\u00a0',' ').replace('\t',' ')
text = re.sub(r'\s{2,}',' ', text)

This first step removes all invisible characters like:

- \u202f (narrow no-break space)
- \u00a0 (non-breaking space)
- \t (tabulation)

All of them are replaced by a standard space.  

Then, r'\s{2,}' reduces multiple spaces to a single one, ensuring that the script processes a clean and consistent text.

text = text.replace("<<","« ").replace(">>"," »")
text = text.replace("+-+","—").replace("=+=","[…]")

Same mechanism here: I replace specific markers by the expected typographic characters.

if typographie=="fr":
    text = re.sub(r'(?<! )([!?;:])', '\u202f\\1', text)
else:
    text = re.sub(r'\s+([!?;:])', r'\1', text)

And here’s where it gets interesting: **typographic rules change depending on the language**.  

If the language is French, we add a narrow space before specific punctuation marks.  

If it's English, we follow classic English punctuation spacing.

text = text.strip()
text = re.sub(r'<!--.*?-->', '', text)
text = re.sub(r'%.*$', '', text)
return text

Final cleanup: strip() removes leading and trailing spaces.  

The re.sub lines delete HTML-style comments and lines starting with %, both of which I use as internal notes.

##### Punctuation

- ’, ‘, ‛   →   '
- ----, ---, --, –   →   —
- …   →   …
- <<   →   «
- >>   →   »
- +-+   →   —
- =+=   →   […]

##### Comments

- <!-- xxx -->   →   deleted
- %   →   deleted
- #### Font styling
- * xxx *   →   italic
- *µ xxx µ*   →   bold
- *µµ xxx µµ*   →   italic bold
- ££   →   acrostic

##### Titles
- #   →   Title
- ##   →   Author
- ###   →   Compilation Title
- ####   →   Year
- #####   →   Sub-title
- >$   →   Part number

##### Non verse

- <=+ xxx +=>   →   Non-verse text
- <+ xxx +>   →   Quotation
- <++ xxx ++>   →   Quotation’s Author

##### Song structure

- ²cpl1   →   fr: [Couplet 1] en: [Verse 1]
- ²rfn1   →   fr: [Refrain 1] en: [Chorus 1]
- ²pnt   →   fr: [Pont]  en: [Bridge]
- ²rnv   →   fr: [Renvoi]en: [Tag]
- ²itl   →   [Interlude]
- ²int   →   [Intro]
- ²out   →   [Outro]
- ²brp   →   (bis repetitas)

##### Alignments

- <= xxx =>   →   Centered
- <== xxx ==>   →   Right-aligned

##### Stanzas
- >=   →   Regular Line Jump
- >==   →   Medium Line Jump
- >===   →   Big Line Jump

##### Indents
- >+   →   Regular Indent
- >++   →   Medium Indent
- >+++   →   Big Indent
- >++++   →   Right Shift

#### Next step

Once the markup language was defined and the Python conversion script was ready, it was time to move on to the next step:  

**building the pipeline** to publish the poems online.

## Building the project

### The pipeline

The concept is simple: **publish new poems online in a single click**.

Back in the day, we did that with a good old **FTP server** — you connected, dropped the files into the client, and the site was updated.  

It wasn’t exactly “one click,” but it was close enough.

Unfortunately, that era is mostly gone (well, not entirely — I still see multinational companies working like that daily, but never mind).

Now that we live in the **Cloud era**, and since I’m developing my skills on **AWS**, here’s how my pipeline works:

- I write .ryu text files using my homemade markup language, **Lyra**. They’re stored in a *Poetry* subfolder inside my *Writing* directory.
- A **batch script** copies them to my scripts/website folder, where they are grouped with all the PHP/HTML files.
- From there, I push everything to **GitHub** — because my Big Beautiful code deserves to be seen, and GitHub is the place for that.
- A **GitHub Action** zips the project and uploads it to my **S3 bucket** on AWS.
- This S3 upload triggers a **Lambda function** that transfers the archive to my **EC2 instance**.
- On the EC2 instance, a **Python script** unzips the file, converts all .ryu files into **HTML**, and uploads them to my **MySQL** database.

And just like that — my website is updated and all my poetry is online.

In parallel, I also process an **Excel file** containing all French and English content for the website, convert some **Word documents** into HTML, and more.

As you can see, it’s a *fun pipeline*. Stay tuned for more technical details.

### Account creation

Creating all the accounts was actually the last step of the project — but it makes more sense to present it at the beginning of the story.

So, I subscribed to an **AWS account**, created a **VPC**, launched an **EC2 instance**, opened an **S3 bucket**, and registered my domain name **fdelancelot.com** via **Route 53**.

I also configured **IAM** accounts and security roles — but we’ll come back to that later.

I also created a **repository on GitHub**, which allowed me to prepare the entire pipeline from local development to cloud deployment.
### Local development setup (Windows)

I play and record music. For many years, most music production tools weren’t available on Linux — which is one of the reasons I still work on **Windows** today.

On my computer, I have a **Writing** folder that contains all my creative work, organized into subfolders:

- *Novels*
- *Short Novels*
- *Poetry*
- *Scripts* (as in film scripts — not Python 😉)
- *Articles*

When a project is complete, I save it as a plain text file with a .ryu extension.  

From there, I can export it to **LaTeX**, **PDF**, or **HTML**.

For more complex works, like novels and screenplays, I use specific **Word templates** that I’ve refined over time and rely on daily.

So, the very first step of my pipeline is simply to retrieve the files I want to publish online.

#### Batch file & sh file

For poetry, I use a **Batch file** that copies all .ryu files — preserving the folder structure — into my local repository.

The same script also copies .ryu files from the *Short Novels* folder, and .docx files from the *Scripts* folder, into their respective locations in the repository.

Finally, the batch file launches a **Shell script** in **Git Bash** to synchronize the repository with GitHub.

This push will later trigger a **GitHub Action** — but we’ll get to that in the next section.

### GitHub

Since this website also serves as a **portfolio**, I obviously use **GitHub** as an integral part of the pipeline.  

It allows me to showcase my code and keep version control.

When I push the repository using my shell script, it triggers a **GitHub Action** that automatically uploads a **ZIP archive** of the website to my **S3 bucket**.

That was the easy part.  

Now comes the fun part — setting up the entire AWS architecture.

### AWS configuration

I could easily write thousands of lines about how I configured my AWS architecture,  

but to keep things readable, here’s a summarized list of what’s been deployed.

#### VPC

- **CIDR block:** 10.0.0.0/16
- **Public subnet:** 10.0.0.0/20 — for serving the website (Apache on ports 80/443)
- **Private subnet:** 10.0.128.0/20 — reserved for future internal services (e.g. databases)
- **Internet Gateway:** attached to allow internet access
- **Route Table:** properly configured to manage traffic
- **Elastic IP:** bound to ensure persistent domain resolution (13.48.51.102)

An **EC2 instance** is deployed inside this VPC and accessed via SSH using a .pem key.

#### EC2 instance

- Instance type: **Amazon Linux t2.micro** (Free Tier eligible)
- Installed stack: **LAMP** (Apache, MariaDB, PHP) via yum
- Apache configured with virtual hosts, PHP enabled, .htaccess supported
- MariaDB set up with users and database creation
- Python 3.9 installed with:
    - mysql-connector-python
    - pandas, python-dotenv, sqlalchemy, unidecode, etc.
- **Certbot** installed with Let’s Encrypt:
    - Certificates issued for fdelancelot.com and www.fdelancelot.com
    - Cron job planned for auto-renewal
- **SSM Agent** installed and configured for remote commands

#### Amazon S3

- Bucket created to store the deployment archive site.zip
- Bucket policy adjusted to allow EC2 access

#### Amazon Route 53

Domain name **fdelancelot.com** purchased directly through Route 53.

#### Lambda

A **Lambda function** was created to transfer the .zip file from the S3 bucket to the EC2 instance.  

It is triggered automatically upon file upload (either manually or via GitHub Action).

#### Other AWS setup

- **AWS Systems Manager (SSM):** used to execute Lambda commands on EC2
- **Budget:** monthly limit set to 5€

#### IAM

- **Admin user:** for daily use (MFA enabled)
- **Automation user:** for GitHub CI/CD, with policies:
    - AmazonS3FullAccess
    - AmazonSSMFullAccess
    - AmazonSSMManagedInstanceCore
- **EC2 instance role:** with permissions for S3 and SSM
- **Lambda role:** with ssm:SendCommand permission and access to the S3 archive

#### Security

- **MFA** enabled on the root account and other critical users
- **Firewall (Security Groups) properly configured:**
    - Port 22 (SSH): restricted to my own IP only
    - Ports 80 & 443 (HTTP/HTTPS): open for public web access

### Python ecosystem & automation

#### Little sum up

Let’s go over the full deployment sequence from the top:

- **Batch script:** copies all required creative files (poems, scripts, short novels) into the local repository
- **Bash script:** launched by the batch file to **push the repository to GitHub**
- **GitHub Action:** triggered by the push — it zips the project and uploads it to the **S3 bucket**
- **Lambda function:** triggered by the new ZIP file in S3
    - Copies the ZIP to /tmp on the EC2 instance using **SSM**
    - Executes the **deploy.py** script

#### deploy.py

The script **deploy.py** orchestrates the entire deployment process:

- Unzips the archive and moves the content into the appropriate directory
- Copies the .env file from a secure folder on the EC2 instance
- Executes three key Python scripts in sequence:

#### poemstohtml.py

This script converts .ryu poems into HTML.

You’ve seen it before — it transforms the content written in my **Lyra markup** into HTML and uploads each poem into the poems table of the MySQL database.  

Each poem is stored on a single row.

The database table uses lovely French column names:

uid   → A unique ID  
id_logique→ An identifier built from the initials of the compilation and poem  
titre → The title of the poem  
recueil   → The compilation title  
type  → The type (e.g. poem, song, etc.)  
fichier   → The original file name  
contenu   → The full HTML content  
date_import   → I’ll let your French handle this one ;)

The id_logique is all I need to retrieve a specific poem, call an entire compilation, or preserve the intended sorting based on filenames.

#### screenplayconvert.py

This script scans all .docx files from the *Scripts* folder and converts them into .php files ready to be included directly on the website.

There are Python libraries that could do this out of the box — but writing it from scratch was more fun.  

Not the smartest move, maybe, but it was an interesting exercise in craftsmanship.

#### translationxlsx.py

This is the **heart of the website**.

I maintain an Excel file that contains:

- 4 key columns, which I concatenate into a unique identifier formatted like: pagename.section.subsection.number
- 1 column for the English text
- 1 column for the French text

The script uploads the combined key and both language strings into a 3-column MySQL table.

On the website, a **PHP function** calls the correct string depending on the current page and selected language, using the key as an argument.

### File structure

- A complete project structure with folders such as site/, content/, scripts/, etc.
- A .env.example and .gitignore file to avoid committing secrets
- A deploy.ps1 PowerShell script to automate Git operations
- Later replaced by a Bash script deploy.sh for better cross-platform compatibility
- A .bat file created to launch it with a double-click on Windows
- Additional logic added to:
    - Copy .ryu and .docx files from *Poésie*, *Nouvelles*, and *Scripts* folders into the GitHub repository
    - Add optional <meta name="keywords"> tags
    - Generate sitemap.xml and robots.txt
    - Use **JSON-LD** or **microdata** for semantic markup (profile, works, skills)

#### Main sections identified

- **Hero**: a two-column layout with a title, description, call-to-action buttons, social icons, and an image
- **Resume**: includes “About me” and a full résumé — multiple paragraphs followed by a structured CV (summary, skills, experience, education, certifications)
- **Skills**: horizontal bars showing proficiency in front-end and back-end technologies
- **Contact**: info boxes (location, phone, email) and a PHP-powered contact form
- **Footer**: copyright notice and credits

## Website architecture

The purpose of this website is twofold:  

To showcase both my **artistic work** (music, poetry, scripts) and my **technical abilities** with AWS technologies.

Here’s how the site is structured:

- **Home** page, including:
    - Short introduction
    - “About me” section
    - “Skills” section
    - “Résumé” section
    - “Contact” section
- **Projects** page — the one you're reading now
- **Artworks** page, with subsections:
    - Music
    - Poetry
    - Scripts
    - YouTube
    - Short Novels
    - Books
- **Poetry** page — a complete dynamic compilation of all my poems

The entire website is **multilingual (English/French)**.  

A simple PHP function reads the $_GET['l'] variable to determine the page’s language and then retrieves the appropriate content from the MySQL database.
function t(string $key): void {
    global $pdo, $l;
    $stmt = $pdo->prepare("SELECT content FROM translations WHERE lang = :lang AND `key` = :key LIMIT 1");
    $stmt->execute([':lang' => $l, ':key' => $key]);
    $text = $stmt->fetchColumn() ?: "[$key]";
    $text = allowtags($text);
    echo $text;
    }

### PHP architecture

The website is built around a central file: index.php.  

This file loads the following components:

- var.php
- function.php
- header.php
- nav.php
- footer.php

Between the navigation and the footer, one of the main content pages is included, depending on the route:

- home.php
- artwork.php
- poetry.php
- projects.php

There are also 6 .ryu short novel files, which are dynamically converted into PHP and included inside a **collapsible area** triggered by a button — more on that later.

Additionally, two special PHP files — mariup.php and souven.php — each display either a *theater play* or a *screenplay*, while preserving the main layout and architecture of the site.

#### Details of each page

Most pages are generated using the same logic:

A PHP function loops over a translation key formatted like page.section.subsection.number, and continues incrementing the number until there is no more text to fetch.

The **Poetry page** follows this same principle but on a much larger scale — it displays the full contents of the poems table dynamically.

The **Short Novels** section is slightly more advanced:  

It dynamically scans the nouvelles folder for all .ryu files, converts them into HTML, and pairs them with their corresponding summary from the translation table.

Each short novel is then loaded into a **collapse component**, triggered by a button.  

This approach avoids overloading the page with too much content at once, keeping the layout clean and readable.

## Conclusion

### Main goals

When designing this website, I had several goals in mind:

- Create an elegant and responsive website
- Build a fully internationalized interface (i18n), easily scalable to other languages
- Ensure fast loading times
- Develop a one-click deployment pipeline
- Publish my poetry using my **Lyra** markup language, without having to modify it
- Showcase my artistic work (music, writings, etc.)
- Keep the codebase minimalist and clean
- Host everything on AWS, with real and professional infrastructure
- Use it as a technical portfolio for job applications
- Learn new skills through practical implementation
- Use **Python** for core automation
- Stick to a small budget (target: less than 5€/month)
- Build a dynamic website with real-time content
- Design a modular system that is easy to maintain
- Keep URLs clean — without those ugly $_GET[] ?var=name
- Use **MySQL** as the database engine
- Use no CMS or heavy frameworks — build everything from scratch
- Integrate **cron jobs**, structured **JSON**, and custom **JavaScript** widgets

### Path of improvement

I enjoy working with **PHP**, but perhaps it’s time to consider switching to a full **Python framework** like Flask.

Since I prefer minimalist and lightweight websites, adopting **Tailwind CSS** would help eliminate the 98% of unused Bootstrap classes.

Alternatively, I might explore more of Bootstrap’s built-in components — such as **modals** or **carousels**.

- Some error messages are not multilingual yet — I should fix that.
- The pipeline could use better logging, testing, and warnings for failures.
- **Lyra** deserves its own dedicated documentation page — that’s definitely coming soon.
- If I want to explore frontend frameworks, I could start migrating some components to **Vue.js** or **React**.
- A web interface to export my texts in **EPUB**, **PDF**, or **LaTeX** could be very useful.
- Creating a **Lyra validator** could help detect typos or formatting issues, even if the language is simple.
- I could also significantly improve the SEO with **structured data** and a **dynamic sitemap** — that’s probably the next step.

### Tools & syntax we used

#### Local Development Environment

- **Notepad++** – Used primarily to edit my .ryu poetry files
- **Git Bash** – Executes Bash scripts for Git operations
- **Batch (.bat)** – Windows automation for file processing
- **Visual Studio Code (VS Code)** – Main development environment
- **XAMPP** – Local Apache + MariaDB stack for development and offline testing
- **Microsoft Excel** – Manages the multilingual translation table
- **Microsoft Word** – My primary writing tool for books and screenplays
- **Git** – Version control
- **GitHub** – Code hosting and CI/CD with GitHub Actions

#### Programming Languages

- **Python**

- Custom scripts: poemstohtml.py, screenplayconvert.py, translationxlsx.py
- Conversion from **Lyra** to HTML and LaTeX
- Upload to MySQL using mysql-connector-python
- Used in EC2 deployment and orchestration

- **PHP**

- Dynamic content display
- Multilingual functions
- Contact form logic

- **HTML / CSS**

- Site structure
- Minimalist, responsive design

- **JavaScript**

- Vanilla JS for interactions
- Widgets: collapses, counters, triggers

- **SQL (MySQL / MariaDB)**

- Poem storage, translation database, metadata

#### Libraries & Frameworks

- **Bootstrap 5** (via CDN) – Responsive grid system and components (collapse, cards, etc.)
- **AOS.js** – Scroll-triggered animations
- **PureCounter.js** – Animated counters
- **Waypoints.js** – Scroll position triggers
- **Google Fonts** – Online typography
- **Certbot / Let’s Encrypt** – HTTPS certificate generation and renewal

#### AWS Services

- **EC2** (Amazon Linux 2) – Web hosting
- **S3** – Storage for deployment archives
- **Lambda** – Pipeline trigger upon file upload
- **Systems Manager (SSM)** – Remote command execution on EC2
- **IAM** – Role and permission management
- **Route 53** – Domain name management (fdelancelot.com)
- **VPC** – Custom network architecture

#### Server Technologies

- **Apache (httpd)** – Web server
- **MariaDB** – Database engine
- **PHP 8.x** – Dynamic page rendering
- **Python 3.9** – Execution of scripts on the EC2 server
- **SSM Agent** – Enables remote SSM commands
- **YUM** – Package manager for Amazon Linux

#### Custom Structure & Formats

- **Lyra** – My personal markup language for poetry
- .ryu – Custom file extension for poems
- .env – Environment configuration
- .xlsx – Centralized multilingual translation base

#### Automation & DevOps

- **GitHub Actions** – CI/CD automation: ZIP → S3
- **Bash scripts** – Git push and file deployment
- **Batch / PowerShell / Shell** – Windows automation and cross-platform compatibility
- **Python scripts** – File parsing, conversion, database injection
- **Cron** – Automated SSL certificate renewal (Let’s Encrypt)
- **ZIP** – Archiving the site before upload

### Finale analysis

**Did I reach all my goals?**

**🟢Create an elegant and responsive website** — YES

This portfolio is intended to showcase my AWS skills, so it's likely to be viewed by recruiters on desktop.  

That said, we live in a mobile-first world, so responsive design was essential.

It’s not a full “mobile-first” approach, but it is still mobile-friendly.

Aesthetically, I’m no professional designer, but I find the **Black & Gold interface** quite elegant — it matches the mood of my artistic universe.  

I’m especially proud of my custom buttons: transparent at rest, filled on click.

**🟢i18n interface** — YES

The multilingual interface is easy to maintain — a single Excel file holds all the content.  

Adding a new language would be simple (as long as we ignore the time-consuming task of actual translation).

**🟢Fast-loading web page** — YES

AWS seems to perform well, and I did my best to keep the site light: minimal images, CDN-hosted libraries and fonts.

**🟢One-click deployment pipeline** — YES

This is the heart of the project — and it works beautifully.

A single click on a batch file triggers the entire automated pipeline.

**🟢Publishing poetry via my markup language without modifying it** — YES

I made a few minor adjustments to the markup for clarity, and tweaked the Python-to-LaTeX conversion script — it took less than 30 minutes.  

So yes, it's a complete success.

**🟡Displaying my artistic work (music, writings, etc.) ** — Partly YES

I had envisioned something more visually rich: photos of the bands, a music player, embedded videos, etc.

While technically feasible, such additions might overwhelm the visitor.

For now, there are clear links to the bands’ SoundCloud pages and YouTube channels.  

The result is simpler than I imagined, but also more readable — which is a success in itself.

**🟢Keeping minimalist, clean code** — YES

The project includes many files and functions — which is normal given its scope.  

I made a serious effort to avoid redundancy, write clean logic, and add clear comments wherever needed.

**🥇Hosted on AWS with real technical deployment** — YES

AWS deployment was far more challenging than expected.

Studying for the Cloud Practitioner certification is one thing — but *actually building and deploying* something is something else entirely.

From opening an SSH session to assigning roles to Lambda functions, it’s been a deep and rewarding learning experience.

Clearly, an AWS certification alone wouldn’t be enough to be job-ready. Building this project made that very clear.

**🟡Use as a portfolio for job prospection** — YES (hopefully)

I say "yes", but the honest answer is: *hopefully yes*.  

I believe this project reflects what I’m capable of — both technically and artistically.

**🥇Learning new skills** — YES

I’ve definitely learned many new things — primarily on AWS, but also beyond:  

SSH connections, LAMP stack setup, Linux environments, Apache configuration, and custom VPC design.

Studying for the **CCNA certification** helped a lot, especially with the networking side.

I also refined my coding skills and even discovered a few unexpected tricks in CSS.

**🟡Using Python — Partly YES**

I used Python extensively, but I stayed within a comfortable scope — no advanced algorithms or external APIs yet.

**🟢Keeping a small budget (< 5€/month)** — YES

Aside from the domain name purchase, everything else runs at **0€/month** thanks to AWS Free Tier. Perfect.

**🟢Dynamic website** — YES

The site is fully dynamic.  

Almost everything is PHP-based: poems are generated on the fly, and most of the content comes from translation functions or database calls.

**🟢Modular and easy to maintain** — YES

Storing all the text in an Excel file makes updates incredibly easy:  

just insert a new line with the right key, and the PHP function will display it.  

For poems, it’s even simpler: renaming or adding new .ryu files locally is enough — the structure is automatically reflected online.

**🟢Clean URLs (no ugly $_GET[])** — YES

Initially, I built the entire site around a single index.php file, which was elegant — but terrible for SEO.

I later adjusted the URLs to be clean and indexable, and I’m happy with the final result.

**🟡Using MySQL** — Partly YES

I used MySQL, but only for basic queries.  

The only new thing I had to learn was how to **deploy MariaDB** on my EC2 server.  

Still, it’s a solid starting point.

**🟢No framework, built from scratch** — YES (mostly)

I didn’t use any heavy CMS like WordPress, and that was intentional.  

That said, I did rely on some JS libraries (PureCounter, Waypoints, etc.), and of course, **Bootstrap**.

Writing my own JS widgets might have been a fun learning exercise, and maybe I’ll explore that later.

But Bootstrap? It just makes sense.  

It’s lightweight enough, and being just CSS and JS, it doesn’t stop me from coding everything myself.

Using a minimal CSS framework allowed me to focus on the real logic — and that was the right choice.

**And that will be the final word:** using just enough CSS framework to stay efficient, without losing control.  

<<<<<<< HEAD
Thank you for reading this very long text.
=======
Thank you for reading this very long text.
>>>>>>> a61ec1d (Sync 2025-07-19T21-43-11)
