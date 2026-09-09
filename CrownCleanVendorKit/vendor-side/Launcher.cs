using System;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.Net.NetworkInformation;
using System.Security.Cryptography;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading;
using System.Windows.Forms;

namespace CrownCleanLauncher
{
    public class LauncherForm : Form
    {
        private Process mysqlProc;
        private Process phpProc;
        private string baseDir;
        private string licenseFile;

        // Status / detail labels (used during service startup)
        private Label statusLabel;
        private Label detailLabel;
        private Button stopButton;
        private bool stopping = false;

        // Activation UI elements
        private Panel activationPanel;
        private Label actTitle;
        private Label actSub;
        private Label actInstruction;
        private Label systemIdLabel;
        private TextBox activationCodeBox;
        private Button activateButton;
        private Button copyButton;
        private Label actStatusLabel;

        // The RSA public key matching LicenseGuard.php / vendor-side/public.pem
        private const string PUB_KEY_PEM =
            "-----BEGIN PUBLIC KEY-----\n" +
            "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuwZOZfmyCVfihJK6ZnkQ\n" +
            "iIa2ECrFSXS1iw5L6N13ecvMK9rEQpepJ4TqId9J0rRrRawqlXvcz51xa+99+B/H\n" +
            "xiTZzdK1/GnScXgts7PYw+xhQN8jnaFu+rJYwHJzhzzP1bS6t7gugg4IHt6Nk3wA\n" +
            "s9M6g6/X0t57Naq17U2PJrJCZZyogt4xT62S4yCCYFxIeGNozFidxuxX2g8VL2tr\n" +
            "Y2FO4GsI1LpJIFbjVYoHgD9juepRYYZ3WUChBzFj5y8lc6EWwnWIjf5DWZVhW0um\n" +
            "jv8H65w/NN+PJV/nTr4upt9EsKvehXGbw1qu/9xycMyylGGZ30jg9w99VkyfFnb3\n" +
            "lQIDAQAB\n" +
            "-----END PUBLIC KEY-----";

        public LauncherForm()
        {
            baseDir = Path.GetDirectoryName(Application.ExecutablePath);
            licenseFile = Path.Combine(baseDir, "app", "storage", "app", ".ccl");
            BuildUI();
        }

        // ─────────────────────────────────────────────
        //  UI BUILDING
        // ─────────────────────────────────────────────

        private void BuildUI()
        {
            Text = "Crown Clean Laundry";
            ClientSize = new Size(500, 220);
            FormBorderStyle = FormBorderStyle.FixedSingle;
            MaximizeBox = false;
            StartPosition = FormStartPosition.CenterScreen;

            // --- Title ---
            actTitle = new Label
            {
                Text = "Crown Clean Laundry",
                Font = new Font("Segoe UI", 16, FontStyle.Bold),
                ForeColor = Color.FromArgb(25, 60, 120),
                AutoSize = true,
                Location = new Point(24, 18)
            };

            // --- Subtitle ---
            actSub = new Label
            {
                Text = "Portable Laundry Management System",
                Font = new Font("Segoe UI", 9),
                ForeColor = Color.Gray,
                AutoSize = true,
                Location = new Point(26, 52)
            };

            // --- Status label (service startup) ---
            statusLabel = new Label
            {
                Text = "Starting services...",
                Font = new Font("Segoe UI", 11, FontStyle.Bold),
                AutoSize = true,
                Location = new Point(26, 90),
                Visible = false
            };

            // --- Detail label (service startup) ---
            detailLabel = new Label
            {
                Text = "",
                Font = new Font("Segoe UI", 9),
                ForeColor = Color.DimGray,
                AutoSize = true,
                Location = new Point(26, 118),
                MaximumSize = new Size(380, 40),
                Visible = false
            };

            // --- Close button ---
            stopButton = new Button
            {
                Text = "   Close Application   ",
                Font = new Font("Segoe UI", 10),
                AutoSize = true,
                Location = new Point(26, 148),
                BackColor = Color.FromArgb(100, 100, 110),
                ForeColor = Color.White,
                FlatStyle = FlatStyle.Flat
            };
            stopButton.Click += (s, e) => CloseApplication();

            // --- Activation panel (hidden by default) ---
            activationPanel = new Panel
            {
                Location = new Point(0, 80),
                Size = new Size(500, 120),
                Visible = false
            };

            actInstruction = new Label
            {
                Text = "This copy must be activated on this system.",
                Font = new Font("Segoe UI", 9),
                ForeColor = Color.DimGray,
                AutoSize = true,
                Location = new Point(2, 0)
            };

            var sysIdTitle = new Label
            {
                Text = "System ID:",
                Font = new Font("Segoe UI", 9, FontStyle.Bold),
                AutoSize = true,
                Location = new Point(2, 22)
            };

            systemIdLabel = new Label
            {
                Text = GetMachineId(),
                Font = new Font("Consolas", 9),
                AutoSize = true,
                Location = new Point(80, 22)
            };

            copyButton = new Button
            {
                Text = "Copy",
                Font = new Font("Segoe UI", 8),
                AutoSize = true,
                Location = new Point(380, 19),
                FlatStyle = FlatStyle.Flat,
                Height = 24
            };
            copyButton.Click += (s, e) =>
            {
                Clipboard.SetText(systemIdLabel.Text);
                copyButton.Text = "Copied!";
                var t = new System.Windows.Forms.Timer { Interval = 1500 };
                t.Tick += (ss, ee) => { copyButton.Text = "Copy"; ((System.Windows.Forms.Timer)ss).Dispose(); };
                t.Start();
            };

            var codeTitle = new Label
            {
                Text = "Activation Code:",
                Font = new Font("Segoe UI", 9, FontStyle.Bold),
                AutoSize = true,
                Location = new Point(2, 50)
            };

            activationCodeBox = new TextBox
            {
                Font = new Font("Segoe UI", 9),
                Location = new Point(110, 47),
                Size = new Size(300, 23),
                Multiline = false
            };

            activateButton = new Button
            {
                Text = "Activate",
                Font = new Font("Segoe UI", 9, FontStyle.Bold),
                Location = new Point(420, 46),
                Size = new Size(70, 25),
                BackColor = Color.FromArgb(25, 60, 120),
                ForeColor = Color.White,
                FlatStyle = FlatStyle.Flat
            };
            activateButton.Click += ActivateButton_Click;

            actStatusLabel = new Label
            {
                Text = "",
                Font = new Font("Segoe UI", 8),
                AutoSize = true,
                Location = new Point(2, 72),
                MaximumSize = new Size(410, 24)
            };

            activationPanel.Controls.AddRange(new Control[] {
                actInstruction, sysIdTitle, systemIdLabel, copyButton,
                codeTitle, activationCodeBox, activateButton, actStatusLabel
            });

            Controls.Add(actTitle);
            Controls.Add(actSub);
            Controls.Add(statusLabel);
            Controls.Add(detailLabel);
            Controls.Add(activationPanel);
            Controls.Add(stopButton);

            // On shown: check activation, then either show activation UI or start services
            Shown += (s, e) => CheckActivationAndStart();
        }

        // ─────────────────────────────────────────────
        //  ACTIVATION CHECK & FLOW
        // ─────────────────────────────────────────────

        private void CheckActivationAndStart()
        {
            if (File.Exists(licenseFile) && new FileInfo(licenseFile).Length > 10)
            {
                ShowServiceUI();
                System.Threading.Tasks.Task.Run((System.Action)StartAllServices);
            }
            else
            {
                ShowActivationUI();
            }
        }

        private void ShowActivationUI()
        {
            statusLabel.Visible = false;
            detailLabel.Visible = false;
            activationPanel.Visible = true;
            activationCodeBox.Text = "";
            actStatusLabel.Text = "";
        }

        private void ShowServiceUI()
        {
            activationPanel.Visible = false;
            statusLabel.Visible = true;
            detailLabel.Visible = true;
        }

        // ─────────────────────────────────────────────
        //  ACTIVATE BUTTON CLICK
        // ─────────────────────────────────────────────

        private void ActivateButton_Click(object sender, EventArgs e)
        {
            string code = activationCodeBox.Text.Trim();
            if (string.IsNullOrEmpty(code))
            {
                actStatusLabel.ForeColor = Color.Red;
                actStatusLabel.Text = "Please enter the activation code.";
                return;
            }

            actStatusLabel.ForeColor = Color.DimGray;
            actStatusLabel.Text = "Verifying...";
            activateButton.Enabled = false;
            Application.DoEvents();

            try
            {
                File.WriteAllText(licenseFile, code);

                actStatusLabel.ForeColor = Color.Green;
                actStatusLabel.Text = "Activated! Starting application...";

                Thread.Sleep(800);

                ShowServiceUI();
                System.Threading.Tasks.Task.Run((System.Action)StartAllServices);
            }
            catch
            {
                if (code.Length > 10 && code.Length < 1000 && !code.Contains(" "))
                {
                    File.WriteAllText(licenseFile, code);
                    actStatusLabel.ForeColor = Color.Green;
                    actStatusLabel.Text = "Activated! Starting application...";
                    Thread.Sleep(800);
                    ShowServiceUI();
                    System.Threading.Tasks.Task.Run((System.Action)StartAllServices);
                }
                else
                {
                    actStatusLabel.ForeColor = Color.Red;
                    actStatusLabel.Text = "Invalid activation code format.";
                }
            }
            finally
            {
                activateButton.Enabled = true;
            }
        }

        // ─────────────────────────────────────────────
        //  MACHINE ID (matches LicenseGuard::machineId())
        // ─────────────────────────────────────────────

        private string GetMachineId()
        {
            try
            {
                ProcessStartInfo psi = new ProcessStartInfo
                {
                    FileName = "reg",
                    Arguments = "query \"HKLM\\SOFTWARE\\Microsoft\\Cryptography\" /v MachineGuid",
                    UseShellExecute = false,
                    CreateNoWindow = true,
                    RedirectStandardOutput = true
                };
                using (Process p = Process.Start(psi))
                {
                    string output = p.StandardOutput.ReadToEnd();
                    p.WaitForExit();
                    Match m = Regex.Match(output, @"MachineGuid\s+REG_SZ\s+(\S+)");
                    if (m.Success)
                    {
                        return ComputeMd5("CC-" + m.Groups[1].Value.Trim()).ToUpper();
                    }
                }
            }
            catch { }

            // Fallback (matches PHP fallback)
            string alt = Environment.MachineName + "|" + Environment.MachineName + "|" +
                         (Environment.GetEnvironmentVariable("COMPUTERNAME") ?? "");
            return ComputeMd5("CC-" + alt).ToUpper();
        }

        private string ComputeMd5(string input)
        {
            using (var md5 = System.Security.Cryptography.MD5.Create())
            {
                byte[] bytes = Encoding.UTF8.GetBytes(input);
                byte[] hash = md5.ComputeHash(bytes);
                StringBuilder sb = new StringBuilder();
                foreach (byte b in hash)
                    sb.Append(b.ToString("x2"));
                return sb.ToString();
            }
        }



        // ─────────────────────────────────────────────
        //  SERVICE STARTUP (unchanged)
        // ─────────────────────────────────────────────

        private void SetStatus(string text)
        {
            if (InvokeRequired) { Invoke(new Action<string>(SetStatus), text); return; }
            statusLabel.Text = text;
        }

        private void SetDetail(string text)
        {
            if (InvokeRequired) { Invoke(new Action<string>(SetDetail), text); return; }
            detailLabel.Text = text;
        }

        private void StartAllServices()
        {
            try
            {
                SetStatus("Starting database...");
                if (!StartMySql())
                {
                    MessageBox.Show("Database failed to start. See detail below.", "Crown Clean Laundry",
                        MessageBoxButtons.OK, MessageBoxIcon.Error);
                    return;
                }

                SetStatus("Starting web server...");
                if (!StartPhp())
                {
                    MessageBox.Show("Web server failed to start.", "Crown Clean Laundry",
                        MessageBoxButtons.OK, MessageBoxIcon.Error);
                    return;
                }

                SetStatus("Running \u2014 Crown Clean Laundry is ready");
                SetDetail("The app is open in your browser.\nClick \"Close Application\" when done.");
            }
            catch (Exception ex)
            {
                SetDetail("Error: " + ex.Message);
            }
        }

        private bool StartMySql()
        {
            string mysqld = Path.Combine(baseDir, "runtime", "mariadb", "bin", "mysqld.exe");
            string dataDir = Path.Combine(baseDir, "data", "mysql");
            string baseMysql = Path.Combine(baseDir, "runtime", "mariadb");

            if (!File.Exists(mysqld))
            {
                SetDetail("mysqld.exe not found at " + mysqld);
                return false;
            }

            if (PortInUse(3308))
            {
                SetDetail("Port 3308 already in use \u2014 assuming DB already running...");
                return true;
            }

            ProcessStartInfo psi = new ProcessStartInfo
            {
                FileName = mysqld,
                Arguments = string.Format(
                    "--no-defaults --basedir=\"{0}\" --datadir=\"{1}\" --port=3308 --bind-address=127.0.0.1 " +
                    "--character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci --skip-networking=0 --console",
                    baseMysql, dataDir),
                WorkingDirectory = dataDir,
                UseShellExecute = false,
                CreateNoWindow = true
            };

            mysqlProc = Process.Start(psi);

            for (int i = 0; i < 60; i++)
            {
                Thread.Sleep(1000);
                if (mysqlProc.HasExited)
                {
                    SetDetail("mysqld exited unexpectedly. Check data/mysql-error.log");
                    return false;
                }
                if (CanConnectMySql())
                {
                    SetDetail("Database ready (MariaDB on port 3308).");
                    return true;
                }
                SetDetail("Waiting for database to start... (" + (i + 1) + "s)");
            }
            SetDetail("Database did not become ready in 60s.");
            return false;
        }

        private bool CanConnectMySql()
        {
            string mysql = Path.Combine(baseDir, "runtime", "mariadb", "bin", "mysql.exe");
            try
            {
                ProcessStartInfo psi = new ProcessStartInfo
                {
                    FileName = mysql,
                    Arguments = "-h 127.0.0.1 -P 3308 -u laundry_user -pLaundry@2024 -e \"SELECT 1\"",
                    UseShellExecute = false,
                    CreateNoWindow = true,
                    RedirectStandardError = true,
                    RedirectStandardOutput = true
                };
                using (Process p = Process.Start(psi))
                {
                    p.WaitForExit(5000);
                    return p.ExitCode == 0;
                }
            }
            catch { return false; }
        }

        private bool StartPhp()
        {
            string php = Path.Combine(baseDir, "runtime", "php", "php.exe");
            string appPublic = Path.Combine(baseDir, "app", "public");

            if (!File.Exists(php))
            {
                SetDetail("php.exe not found at " + php);
                return false;
            }

            if (PortInUse(8000))
            {
                SetDetail("Port 8000 already in use \u2014 assuming web server already running...");
                OpenBrowser();
                return true;
            }

            ProcessStartInfo psi = new ProcessStartInfo
            {
                FileName = php,
                Arguments = string.Format(
                    "-d opcache.enable=1 -d opcache.enable_cli=1 -d opcache.memory_consumption=256 " +
                    "-d opcache.interned_strings_buffer=32 -d opcache.max_accelerated_files=20000 " +
                    "-d opcache.revalidate_freq=0 -d opcache.validate_timestamps=0 " +
                    "-d \"zend_extension={2}\" " +
                    "-S 127.0.0.1:8000 -t \"{0}\" \"{1}\"",
                    appPublic, Path.Combine(appPublic, "server.php"),
                    Path.Combine(baseDir, "runtime", "php", "ext", "php_opcache.dll")),
                WorkingDirectory = baseDir,
                UseShellExecute = false,
                CreateNoWindow = true
            };

            phpProc = Process.Start(psi);

            for (int i = 0; i < 20; i++)
            {
                Thread.Sleep(1000);
                if (phpProc.HasExited)
                {
                    SetDetail("php -S exited unexpectedly.");
                    return false;
                }
                if (HttpResponds("http://127.0.0.1:8000"))
                {
                    SetDetail("Web server ready on http://localhost:8000");
                    OpenBrowser();
                    return true;
                }
            }
            SetDetail("Web server did not respond in 20s.");
            return false;
        }

        private void OpenBrowser()
        {
            try
            {
                Process.Start(new ProcessStartInfo("http://localhost:8000") { UseShellExecute = true });
            }
            catch { }
        }

        private bool PortInUse(int port)
        {
            var props = IPGlobalProperties.GetIPGlobalProperties();
            foreach (var l in props.GetActiveTcpListeners())
                if (l.Port == port) return true;
            return false;
        }

        private bool HttpResponds(string url)
        {
            try
            {
                using (var wc = new System.Net.WebClient())
                {
                    wc.Headers.Add("User-Agent", "CrownLauncher");
                    string data = wc.DownloadString(url);
                    return data != null;
                }
            }
            catch { return false; }
        }

        private void CloseApplication()
        {
            var result = MessageBox.Show(
                "Are you sure you want to close Crown Clean Laundry?\n\nThis will stop the application and all services.",
                "Confirm Close",
                MessageBoxButtons.YesNo,
                MessageBoxIcon.Question);
            if (result == DialogResult.Yes)
            {
                stopping = true;
                StopAllProcesses();
                Application.Exit();
            }
        }

        private void StopAllProcesses()
        {
            try
            {
                string mysqladmin = Path.Combine(baseDir, "runtime", "mariadb", "bin", "mysqladmin.exe");
                ProcessStartInfo psi = new ProcessStartInfo
                {
                    FileName = mysqladmin,
                    Arguments = "-h 127.0.0.1 -P 3308 -u root -pCrownLocal#2026!Db shutdown",
                    UseShellExecute = false,
                    CreateNoWindow = true
                };
                using (Process p = Process.Start(psi)) { p.WaitForExit(15000); }
            }
            catch { }

            if (phpProc != null && !phpProc.HasExited) { try { phpProc.Kill(); } catch { } }
            if (mysqlProc != null && !mysqlProc.HasExited) { try { mysqlProc.Kill(); } catch { } }
        }
    }

    internal static class Program
    {
        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new LauncherForm());
        }
    }
}
