import 'package:eczema/Screens/image.dart';
import 'package:flutter/material.dart';

class PatientInfoPage extends StatefulWidget {
  @override
  _PatientInfoPageState createState() => _PatientInfoPageState();
}

class _PatientInfoPageState extends State<PatientInfoPage> {
  final _formKey = GlobalKey<FormState>();

  TextEditingController nameController = TextEditingController();
  TextEditingController ageController = TextEditingController();
  TextEditingController contactController = TextEditingController();
  TextEditingController emailController = TextEditingController();
  TextEditingController medicalHistoryController = TextEditingController();

  String? selectedGender;
  String? selectedDiet;
  String? selectedSkinType;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Color(0xFFF7F7F7),
      appBar: AppBar(
        title: Text('Patient Information', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
        backgroundColor: Color(0xFF4CAF50),
        centerTitle: true,
        elevation: 4,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.only(bottomLeft: Radius.circular(20), bottomRight: Radius.circular(20)),
        ),
      ),
      body: SingleChildScrollView(
        padding: EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text("Personal Information", style: _sectionTitleStyle()),
              _buildTextField(nameController, 'Full Name', Icons.person),
              _buildTextField(ageController, 'Age', Icons.calendar_today, keyboardType: TextInputType.number),
              _buildDropdown('Select Gender', ['Male', 'Female', 'Other'], Icons.wc, (value) {
                setState(() => selectedGender = value);
              }),
              _buildTextField(contactController, 'Contact Number', Icons.phone, keyboardType: TextInputType.phone),
              _buildTextField(emailController, 'Email', Icons.email, keyboardType: TextInputType.emailAddress),

              SizedBox(height: 20),

              Text("Medical History", style: _sectionTitleStyle()),
              _buildTextField(medicalHistoryController, 'Any pre-existing medical conditions?', Icons.history, maxLines: 3),

              SizedBox(height: 20),

              Text("Lifestyle & Skin Condition", style: _sectionTitleStyle()),
              _buildDropdown('Diet Preference', ['Vegetarian', 'Non-Vegetarian', 'Vegan'], Icons.food_bank, (value) {
                setState(() => selectedDiet = value);
              }),
              _buildDropdown('Skin Type', ['Oily', 'Dry', 'Combination', 'Sensitive'], Icons.face, (value) {
                setState(() => selectedSkinType = value);
              }),

              SizedBox(height: 30),

              Center(
                child: ElevatedButton(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Color(0xFF4CAF50),
                    padding: EdgeInsets.symmetric(horizontal: 60, vertical: 18),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
                    textStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.w600),
                  ),
                  onPressed: () {
                    if (_formKey.currentState?.validate() ?? false) {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => ImageUploadPage(),
                        ),
                      );
                    }
                  },
                  child: Text('Submit', style: TextStyle(color: Colors.white)),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  TextStyle _sectionTitleStyle() {
    return TextStyle(
      fontSize: 22,
      fontWeight: FontWeight.w700,
      color: Colors.black87,
      letterSpacing: 0.5,
    );
  }

  Widget _buildTextField(
      TextEditingController controller, String label, IconData icon,
      {TextInputType keyboardType = TextInputType.text, int maxLines = 1}) {
    return Padding(
      padding: const EdgeInsets.only(top: 12),
      child: TextFormField(
        controller: controller,
        keyboardType: keyboardType,
        maxLines: maxLines,
        decoration: _inputDecoration(label, icon),
        validator: (value) => value!.isEmpty ? 'This field is required' : null,
      ),
    );
  }

  Widget _buildDropdown(String label, List<String> items, IconData icon, Function(String?) onChanged) {
    return Padding(
      padding: const EdgeInsets.only(top: 12),
      child: Container(
        padding: EdgeInsets.symmetric(horizontal: 8),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          boxShadow: [
            BoxShadow(
              color: Colors.grey.withOpacity(0.1),
              spreadRadius: 1,
              blurRadius: 3,
            ),
          ],
        ),
        child: DropdownButtonFormField<String>(
          decoration: InputDecoration(
            labelText: label,
            prefixIcon: Icon(icon, color: Color(0xFF4CAF50)),
            border: InputBorder.none,
            contentPadding: EdgeInsets.symmetric(vertical: 10, horizontal: 10),
          ),
          items: items.map((String value) {
            return DropdownMenuItem<String>(value: value, child: Text(value));
          }).toList(),
          onChanged: onChanged,
          validator: (value) => value == null ? 'Please select an option' : null,
        ),
      ),
    );
  }
}

InputDecoration _inputDecoration(String label, IconData icon) {
  return InputDecoration(
    labelText: label,
    prefixIcon: Icon(icon, color: Color(0xFF4CAF50)),
    filled: true,
    fillColor: Colors.white,
    border: OutlineInputBorder(
      borderRadius: BorderRadius.circular(12),
      borderSide: BorderSide.none,
    ),
    contentPadding: EdgeInsets.symmetric(vertical: 15, horizontal: 12),
  );
}